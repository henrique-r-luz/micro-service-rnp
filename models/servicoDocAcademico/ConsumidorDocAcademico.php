<?php

namespace microServiceRnp\models\servicoDocAcademico;

use Yii;
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
        // echo 'lopp ' . PHP_EOL;
        // Yii::info("loop");
        while (true) {
            try {
                echo 'lopp ' . PHP_EOL;
                Yii::info("loop2222");
                1 / 0;
                $channel = $this->conexao->channel();
                $channel->queue_declare(self::FILA, false, true, false, false);

                $callback = function ($msg) {
                    $this->executaJob($msg);
                };

                $channel->basic_qos(null, 1, false);
                $channel->basic_consume(self::FILA, '', false, false, false, false, $callback);
                while ($channel->is_consuming()) {
                    echo 'lopp channel ' . PHP_EOL;
                    $channel->wait();
                }
                sleep(1);
            } catch (\Throwable $exception) {
                Yii::info("loop2222");
                echo $exception->getMessage() . PHP_EOL;
                sleep(5);
            }
        }
    }


    private function executaJob($msg)
    {
        echo ' [x] Received Mensagem de ', $msg->getBody();
        $msg->ack();
    }
}
