<?php

namespace kevocode\tools\commons\traits;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

/**
 * Trait que provee la autenticación para una API Rest, los métodos de autenticación por defecto son:
 *  * HttpBasicAuth
 *  * HttpBearerAuth
 *
 * @package kevocode
 * @subpackage tools\commons\traits
 * @category traits
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 0.0.0
 */
trait ApiAuthentication
{
    private $aditionAuthMethods = [];

    /**
     * Configuración de comportamientos para el modelo.
     * 
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                [
                    'class' => '\yii\filters\auth\HttpBasicAuth',
                    'auth' => function ($username, $password) {
                        $user = Yii::$app->user->identityClass::findByUsername($username);
                        if ($user && $user->validatePassword($password)) {
                            return $user;
                        }
                    }
                ],
                HttpBearerAuth::className(),
            ],
        ];
        return $behaviors;
    }
}