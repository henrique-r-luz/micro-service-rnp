<?php

use app\lib\componentes\RabbitMqConfig;



return [
    'class' => RabbitMqConfig::class,
    'host' => 'rabbitmq',
    'porta' => '5672',
    'login' => 'guest',
    'senha' => 'guest'
];
