<?php

namespace microServiceRnp\models;

use Yii;
use Exception;
use microServiceRnp\models\TipoDiploma;
use microServiceRnp\models\SingletonToken;
use microServiceRnp\lib\helper\CajuiException;

class CallRnp
{

    public static function verificaRNPAtivo()
    {
        try {
            SingletonToken::getToken();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public static function createDocRNP($param)
    {
        try {
            if (isset($param['pdf'])) {
                $documento = self::enviaRvddCurl($param);
                return $documento;
            } else {
                $parametos = [
                    'documentType' => TipoDiploma::get($param['itensDiploma']->tipo_diploma_id_rnp),
                    'documentData' => $param['arquivo'],
                ];
                $documento = self::enviaCurl($parametos);
                return $documento;
            }
        } catch (Exception $e) {
            throw new CajuiException('Problemas na criação do documento ' . $e->getMessage());
        }
    }


    public static function getUrl()
    {
        return Yii::$app->rnp->url_rnp_api . '/api';
    }




    private static function enviaCurl($param)
    {
        try {
            $data = $param;
            $postdata = ($data);
            $token = SingletonToken::getToken();
            $authorization = 'Authorization: ' . $token['Authorization'];
            $ch = curl_init(self::getUrl() . '/documents');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_STDERR, $verbose = fopen('php://temp', 'rw+'));
            curl_setopt($ch, CURLOPT_FILETIME, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data', $authorization]);
            $result = curl_exec($ch);
            curl_close($ch);
            $resp = json_decode($result, true);
            if (isset($resp['documentId'])) {
                if ($resp['documentId'] == 0) {
                    throw new CajuiException('O documento não pode ser criadao! ');
                }
                return $resp['documentId'];
            } else {
                $erro = 'Falha de execução na criação do documento! ';
                if (isset($resp['error']['message'])) {
                    $erro .= $resp['error']['message'];
                }
                throw new CajuiException($erro);
            }
        } catch (\Exception $e) {

            throw new CajuiException($e->getMessage());
        }
    }

    private static function enviaRvddCurl($param)
    {
        try {
            $cfile = curl_file_create($param['pdf'], 'application/pdf', 'rvdd');
            $data = [
                "documentType" => TipoDiploma::get($param['itensDiploma']->tipo_diploma_id_rnp),
                "documentData" => strval($param['arquivo']),
                "documentFile" => $cfile
            ];
            $postdata = ($data);
            $token = SingletonToken::getToken();
            $authorization = 'Authorization: ' . $token['Authorization'];
            $ch = curl_init(self::getUrl() . '/documents');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_STDERR, $verbose = fopen('php://temp', 'rw+'));
            curl_setopt($ch, CURLOPT_FILETIME, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data', $authorization]);
            $result = curl_exec($ch);
            curl_close($ch);
            $resp = json_decode($result, true);
            if (isset($resp['documentId']) && $resp['documentId'] == 0) {

                throw new CajuiException('Erro ao criar o RVDD !');
            }
            if (isset($resp['documentId']) && $resp['documentId'] != 0) {
                return $resp['documentId'];
            }
            throw new CajuiException($resp['error']['message']);
        } catch (\Exception $e) {
            throw new CajuiException('O RVDD não pode ser criadao! ');
        }
    }
}
