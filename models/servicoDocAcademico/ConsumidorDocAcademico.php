<?php

namespace microServiceRnp\models\servicoDocAcademico;

use Yii;
use microServiceRnp\models\CallRnp;
use microServiceRnp\models\TipoDiploma;
use microServiceRnp\models\ItensDiploma;
use microServiceRnp\lib\helper\CajuiHelper;
use microServiceRnp\models\StatusDocumento;
use microServiceRnp\models\ConexaoSingleton;
use microServiceRnp\lib\helper\CajuiException;
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
            /* echo $e->getMessage() . PHP_EOL;
            sleep(5);*/
        }
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
            $this->enviaDocRnp($itensDiploma, $array_json);
        } catch (\Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
        } finally {
            $msg->ack();
        }
    }


    private function enviaDocRnp($itensDiploma, $array_json)
    {
        $json =  $this->geraPdfA($array_json);
        //envia json para rnp
        $param = [
            'itensDiploma' => $itensDiploma,
            'arquivo' => $json,
        ];
        $documentId = CallRnp::createDocRNP($param);
        $itensDiploma->doc_rnp_id = $documentId;
        $itensDiploma->status = StatusDocumento::STATUS_RNP;

        if (!$itensDiploma->update(false)) {
            // lança erro
        }
    }


    private function geraPdfA($array_json)
    {

        $array_aux = $array_json;
        foreach ($array_json['data']['RegistroReq']['DocumentacaoComprobatoria'] as $id => $doc) {
            if (isset($doc['Documento']['Arquivo']) && (empty($doc['Documento']['Arquivo']) || $doc['Documento']['Arquivo'] == '')) {
                continue;
            }
            $documento = base64_decode($doc['Documento']['Arquivo']);
            $nomeArquivo = "doc" . $array_json['meta']['yourNumber'] . '_' . $id;
            $pdfJson = fopen(Yii::getAlias('@tmp') . '/' . $nomeArquivo . ".pdf", "w");
            fwrite($pdfJson, $documento);
            fclose($pdfJson);

            CajuiHelper::convertToPdfA(
                Yii::getAlias('@tmp') . '/' . $nomeArquivo . ".pdf",
                Yii::getAlias('@tmp') . '/' . $nomeArquivo . "_pdfa_" . ".pdf"
            );
            $documentoPdfA = \base64_encode(file_get_contents(Yii::getAlias('@tmp') . '/' . $nomeArquivo . "_pdfa_" . ".pdf"));
            $array_aux['data']['RegistroReq']['DocumentacaoComprobatoria'][$id]['Documento']['Arquivo'] = $documentoPdfA;
            //remove pdfs
            unlink(Yii::getAlias('@tmp') . '/' . $nomeArquivo . ".pdf");
            unlink(Yii::getAlias('@tmp') . '/' . $nomeArquivo . "_pdfa_" . ".pdf");
        }


        return json_encode($array_aux, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
