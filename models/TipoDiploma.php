<?php

namespace microServiceRnp\models;

class TipoDiploma
{

    public const ACADEMICO = 'academic_doc_mec_degree';
    public const DIGITAL_PUBLICO = 'digital_degree';
    public const VISUAL = 'visual_rep_degree';
    public const HISTORICO_FINAL = 'final_academic_transcript';
    public const HISTORICO_FINAL_VISUAL = 'final_academic_transcript_visual';


    public static function all()
    {
        return [
            self::HISTORICO_FINAL_VISUAL => '1',
            self::ACADEMICO => '4',
            self::DIGITAL_PUBLICO => '2',
            self::VISUAL => '5',
            self::HISTORICO_FINAL => '3'
        ];
    }

    public static function get($tipo)
    {
        $all = self::all();

        if (isset($all[$tipo])) {
            return $all[$tipo];
        }

        return 'Não existe';
    }
}
