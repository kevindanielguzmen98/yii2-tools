<?php

namespace kevocode\tools\rest\actions;

use Yii;
use kevocode\tools\commons\DynamicModel;
use yii\base\InvalidConfigException;

/**
 * Acción para realizar un login normal por nombre de usuario y contraseña.
 *
 * @package kevocode
 * @subpackage tools\rest\actions
 * @category actions
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 0.0.0
 */
class LoginAction extends \yii\base\Action
{
    /**
     * Evento para la validación del acceso.
     */
    public $checkAccess;

    /**
     * Definición de modelo de usuario para validación de inicio de sesión.
     * 
     * @var string
     */
    public $userClass = null;

    /**
     * Sobreestricuta de método inicializador.
     * 
     */
    public function init()
    {
        parent::init();
        if (is_null($this->userClass)) {
            $this->userClass = Yii::$app->user->identityClass;
        }
    }

    /**
     * Función para correr la acción
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }
        $model = $this->getDynamicModel();
        if ($model->validate()) {
            $user = $this->userClass::findByUsername($model->username);
            if (!$user->validatePassword($model->password)) {
                $model->addError('password', Yii::t('app', 'Password is incorrect'));
            } else {
                $user->setAccessToken();
                $model = $user;
            }
        }
        return $model;
    }

    /**
     * Define el modélo dinámico para la autenticación normal.
     * 
     * @return DynamicModel
     */
    private function getDynamicModel()
    {
        $request = Yii::$app->request;
        $rules = [
            [['username', 'password'], 'required'],
            [['username'], 'exist', 'targetClass' => $this->userClass, 'targetAttribute' => 'username', 'filter' => function ($query) {
                $query->andWhere([$this->userClass::STATUS_COLUMN => $this->userClass::STATUS_ACTIVE]);
                return $query;
            }, 'message' => Yii::t('app', 'There is not user with this username or is inactive.')]
        ];
        return DynamicModel::withRules([
            'username' => $request->post('username'),
            'password' => $request->post('password')
        ], $rules);
    }
}