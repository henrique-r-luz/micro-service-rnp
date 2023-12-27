<?php

namespace microServiceRnp\commands;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;

class GerenciaConsumidorController extends Controller
{

    const urlBase = '/micro-service-rnp';
    public function actionRun(int $totalProcesso)
    {

        for ($i = 0; $i < $totalProcesso; $i++) {
            $this->abriProcessos();
        }
    }
    private function abriProcessos()
    {
        $consoleCommand = Yii::$app->basePath . '/yii   consumidor/run';
        $backgroundCommand = Console::isRunningOnWindows() ? 'start /B ' : 'nohup ';
        $command = $backgroundCommand . ' php ' .  $consoleCommand . ' > ' . '/dev/null 2>&1 &';
        exec($command);
    }
}
