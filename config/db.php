<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'pgsql:host=db_cajui;dbname=db_cajui',
    'username' => 'cajui',
    'password' => 'cajui@ifnmg',
    'charset' => 'utf8',
    'enableSchemaCache' => YII_DEBUG ? false : true,
    'schemaMap' => [
        'pgsql' => [
            'class' => \yii\db\pgsql\Schema::class,
            'columnSchemaClass' => [
                'class' => \yii\db\pgsql\ColumnSchema::class,
                'deserializeArrayColumnToArrayExpression' => false,
            ],
        ],
    ],
];
