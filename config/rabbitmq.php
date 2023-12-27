<?php

use microServiceRnp\lib\componentes\RabbitMqConfig;



return [
    'class' => RabbitMqConfig::class,
    'host' => 'rabbitmq',
    'porta' => '5672',
    'login' => 'guest',
    'senha' => 'guest'
];
