<?php

namespace microServiceRnp\models;

use Yii;

use GuzzleHttp\Client as ClientGuzz;

class SingletonToken
{
    public static function getToken()
    {
        $subclass = 'SingletonToken';

        if (!Yii::$app->cache->exists($subclass)) {
            Yii::$app->cache->set($subclass, self::postToken(), 780);
        }
        if (empty(Yii::$app->cache->get($subclass))) {
            return self::postToken();
        }
        return Yii::$app->cache->get($subclass);
    }

    public static function postToken()
    {
        $client = new ClientGuzz();
        $response = $client->request(
            'POST',
            CallRnp::getUrl() . '/users/auth',
            [
                'form_params' => [
                    "email" => Yii::$app->rnp->email,
                    "password" => Yii::$app->rnp->password,
                ],
            ]
        );
        $token = json_decode($response->getBody()->getContents(), true);
        return [
            'Authorization' => 'Bearer ' . $token['accessToken'],
            'Accept' => 'application/json',
        ];
    }
}
