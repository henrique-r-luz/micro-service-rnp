<?php

namespace microServiceRnp\lib\helper;

use RuntimeException;

class CajuiHelper
{

    public static function convertToPdfA($arquivo, $outputFile)
    {
        // Check to see if the profile dir exists and is writeable
        if (is_dir($arquivo) && !is_writable($arquivo) && !file_exists($arquivo)) {
            throw new RuntimeException(
                'O arquivo ("' . $arquivo . '") não foi encontrato ou não possui permissão!'
            );
        }
        // Build the cmd to run
        $cmd = 'gs'
            . ' -dPDFA'
            . ' -dBATCH'
            . ' -dNOPAUSE'
            . ' -sColorConversionStrategy=UseDeviceIndependentColor'
            . ' -sDEVICE=pdfwrite'
            . ' -dPDFACompatibilityPolicy=1'
            . ' -sOutputFile=' . $outputFile
            . ' ' . $arquivo;
        exec($cmd);
        return is_dir($outputFile) && !is_writable($outputFile);
    }
}
