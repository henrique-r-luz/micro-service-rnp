<?php

namespace app\models;

use Yii;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConexaoSingleton
{

    private static ?ConexaoSingleton $conexao = null;

    private function __construct()
    {
    }

    public static function getInstance(): ConexaoSingleton
    {

        if (self::$conexao === null) {
            self::$conexao = new static();
        }
        return self::$conexao;
    }

    public  function conexaoRabbitmq()
    {
        return new AMQPStreamConnection(
            Yii::$app->rabbitmq->host,
            Yii::$app->rabbitmq->porta,
            Yii::$app->rabbitmq->login,
            Yii::$app->rabbitmq->senha
        );
    }
}
