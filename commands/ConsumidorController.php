<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */


namespace microServiceRnp\commands;


use yii\console\Controller;
use microServiceRnp\models\servicoDocAcademico\ConsumidorDocAcademico;
use Throwable;

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
    public function actionRun(int $consumidor_id)
    {
        while (true) {
            try {
                $consumidorDocAcademico = new ConsumidorDocAcademico();
                $consumidorDocAcademico->run($consumidor_id);
            } catch (Throwable $e) {
                echo $e->getMessage();

                sleep(5);
            } finally {
                unset($consumidorDocAcademico);
            }
        }
    }
}
