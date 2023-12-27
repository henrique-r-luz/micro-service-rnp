<?php

/**
 * Este arquivo é parte do
 *    ___       _       _
 *   / __\__ _ (_)_   _(_)
 *  / /  / _` || | | | | |
 * / /__| (_| || | |_| | |
 * \____/\__,_|/ |\__,_|_|
 *           |__/
 *                 Um sistema integrado do IFNMG
 * PHP version 7
 *
 * @copyright Copyright (c) 2016, IFNMG
 * @license   http://cajui.ifnmg.edu.br/license/ MIT License
 * @link      http://cajui.ifnmg.edu.br/
 */

namespace microServiceRnp\models;


use yii\db\ActiveRecord;

/**
 * Classe model da tabela "graduacao.itens_diploma".
 *
 * @property int $id
 * @property int $tipo_diploma_id_rnp
 * @property int $diploma_digital_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $updated_by
 * @property int $created_by
 * @property User $createdBy
 * @property DiplomaDigital $diplomaDigital
 * @property TipoDiploma $tipoDiploma
 * @property User $updatedBy
 * @author xxxx
 * @since  3.1.1
 */
class ItensDiploma extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'graduacao.itens_diploma';
    }

    public function rules(): array
    {
        return [
            [['tipo_diploma_id_rnp', 'diploma_digital_id', 'data'], 'required'],
            [['tipo_diploma_id_rnp', 'status'], 'string'],
            [['livro_registro_id', 'dados_doc'], 'safe'],
            [['diploma_digital_id', 'created_at', 'updated_at', 'updated_by', 'created_by', 'doc_rnp_id'], 'integer'],
        ];
    }
}
