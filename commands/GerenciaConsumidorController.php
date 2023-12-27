<?php

namespace microServiceRnp\commands;

use yii\console\Controller;

class GerenciaConsumidorController extends Controller
{
    public function __construct()
    {
    }

    public function actionRun(int $totalProcesso)
    {

        for ($i = 0; $i < $totalProcesso; $i++) {
            
        }
    }
}
