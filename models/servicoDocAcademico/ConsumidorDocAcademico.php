<?php

namespace microServiceRnp\models\servicoDocAcademico;

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


    public function run()
    {
        while (true) {
            try {

                $channel = $this->conexao->channel();
                $channel->queue_declare(self::FILA, false, true, false, false);
                echo " [*] Waiting for messages. To exit press CTRL+C\n";

                $callback = function ($msg) {
                    $this->executaJob($msg);
                };

                $channel->basic_qos(null, 1, false);
                $channel->basic_consume(self::FILA, '', false, false, false, false, $callback);
                while ($channel->is_consuming()) {
                    $channel->wait();
                }
            } catch (\Throwable $exception) {
                echo $exception->getMessage() . PHP_EOL;
                sleep(1);
            }
        }
    }


    private function executaJob($msg)
    {
        echo ' [x] Received Mensagem de ', $msg->getBody();
        $msg->ack();
    }
}
