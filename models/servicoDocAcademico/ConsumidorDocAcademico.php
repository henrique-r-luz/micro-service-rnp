<?php

namespace microServiceRnp\models\servicoDocAcademico;

use microServiceRnp\lib\helper\CajuiException;
use microServiceRnp\models\CallRnp;
use microServiceRnp\models\TipoDiploma;
use microServiceRnp\models\ItensDiploma;
use microServiceRnp\models\StatusDocumento;
use microServiceRnp\models\ConexaoSingleton;
use PhpAmqpLib\Connection\AMQPStreamConnection;



class ConsumidorDocAcademico
{
    private AMQPStreamConnection $conexao;
    const FILA = 'fila_doc_academica';
    public function __construct()
    {
        $this->conexao =  ConexaoSingleton::getInstance()->conexaoRabbitmq();
    }


    public function run(int $consumidor_id)
    {
        //  while (true) {

        try {
            $channel = $this->conexao->channel();
            $channel->queue_declare(self::FILA, false, true, false, false);
            $callback = function ($msg) {
                $this->executaJob($msg);
            };
            $channel->basic_qos(null, 1, false);
            $channel->basic_consume(self::FILA, '', false, false, false, false, $callback);
            while ($channel->is_consuming()) {
                $channel->wait();
            }
        } catch (\Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
            sleep(5);
        } catch (CajuiException $e) {
            echo $e->getMessage() . PHP_EOL;
            sleep(5);
        }
        // }
    }


    private function executaJob($msg)
    {
        try {
            echo 'inicia' . \PHP_EOL;
            //$msg->ack();
            $json = $msg->getBody();
            $array_json = \json_decode($json, true);
            $diplomaDigital_id = $array_json['meta']['groupId'];
            $itensDiploma = ItensDiploma::find()->where(['diploma_digital_id' => $diplomaDigital_id])
                ->andWhere(['tipo_diploma_id_rnp' => TipoDiploma::ACADEMICO])
                ->one();
            if (empty($itensDiploma)) {
                //lança erro
                echo 'emptyyyy' . \PHP_EOL;
            }
            //inicia processamento do documento 
            $itensDiploma->status = StatusDocumento::PROCESSANDO;

            if (!$itensDiploma->update(false)) {
                //lança erro

            }
            $this->enviaDocRnp($itensDiploma, $json);
        } catch (\Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
        } finally {
            $msg->ack();
        }
    }


    private function enviaDocRnp($itensDiploma, $json)
    {
        //envia json para rnp
        $param = [
            'itensDiploma' => $itensDiploma,
            'arquivo' => $json,
        ];
        $documentId = CallRnp::createDocRNP($param);
        $itensDiploma->doc_rnp_id = $documentId;
        $itensDiploma->status = StatusDocumento::STATUS_RNP;
        /* $itensDiploma->dados_doc = $fotoDadosDiploma;
    $itensDiploma->data = $fotoDadosDiploma['pessoal_curso']['pessoal'][LabelDadosQuery::data_emissao_historico]
        . ' ' . $fotoDadosDiploma['pessoal_curso']['pessoal'][LabelDadosQuery::hora_emissao_historico];*/

        if (!$itensDiploma->update(false)) {
            // lança erro
        }
    }
}
