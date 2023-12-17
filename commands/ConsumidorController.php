<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;


/**
 * Undocumented class
 *
 * @author Henrique Luz
 */
class ConsumidorController extends Controller
{
    /**
     * Script de inicialização do consumidor
     *
     * @param string $message
     * @return void
     * @author Henrique Luz
     */
    public function actionRun($message = 'hello world')
    {
        while (true) {
            \sleep(2);
            echo $message . "\n";
        }

        // return ExitCode::OK;
    }
}
