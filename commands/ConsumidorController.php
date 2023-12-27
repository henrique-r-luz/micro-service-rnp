<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;


use parallel\Runtime;
use yii\console\Controller;
use app\models\servicoDocAcademico\ConsumidorDocAcademico;



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
    public function actionRun()
    {
        /*while (true) {
            \sleep(2);
            echo $message . "\n";
        }*/

        //$consumidorDocAcademico->run();

        $consumidorDocAcademico = new ConsumidorDocAcademico();

        $consumidor1 =  new Runtime();
        $consumidor1->run(function () use ($consumidorDocAcademico) {
            echo "porcesso 1" . \PHP_EOL;
            ConsumidorDocAcademico::run();
            //$consumidorDocAcademico = new ConsumidorDocAcademico();
            //$consumidorDocAcademico->run();
        }, []);


        $consumidor2 =  new Runtime();
        $consumidor2->run(function () use ($consumidorDocAcademico) {
            echo "porcesso 2" . \PHP_EOL;
            ConsumidorDocAcademico::run();
            //$consumidorDocAcademico = new ConsumidorDocAcademico();
            //$consumidorDocAcademico->run();
        }, []);


        $consumidor3 =  new Runtime();
        $consumidor3->run(function () use ($consumidorDocAcademico) {
            echo "porcesso 3" . \PHP_EOL;
            //$consumidorDocAcademico = new ConsumidorDocAcademico();
            ConsumidorDocAcademico::run();
        }, []);



        echo " [*] Principal \n";

        // return ExitCode::OK;
    }
}
