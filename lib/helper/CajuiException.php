<?php

namespace microServiceRnp\lib\helper;

use Exception;
use Throwable;

class CajuiException extends Exception
{

    public $idMsg;
    // Redefine a exceção de forma que a mensagem não seja opcional
    public function __construct($message = null, $code = 0, Throwable $previous = null, $idMsg = null)
    {
        // código
        $this->idMsg = $idMsg;
        // garante que tudo está corretamente inicializado
        parent::__construct($message, $code, $previous);
    }
}
