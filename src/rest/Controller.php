<?php

namespace kevocode\tools\rest;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\base\InvalidConfigException;

/**
 * Controllador base para la API Rest.
 * 
 * @package kevocode
 * @subpackage tools\rest
 * @category controllers
 * 
 * @version 0.0.1
 * @since 0.0.0
 */
class Controller extends ActiveController
{
    public $searchModel = null;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Definición de evento inicializador del controlador.
     */
    public function init()
    {
        parent::init();
        if (is_null($this->searchModel)) {
            throw new InvalidConfigException(Yii::t('app', 'The searchModel property can not be null.'));
        }
    }

    /**
     * Configuración de comportamientos de la aplicación.
     * 
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * Configuración para las acciones del controlador.
     * 
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [
            $this, 'getDataProvider'
        ];
        $actions['delete'] = [
            'class' => 'kevocode\tools\rest\actions\DeleteAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
        ];
        return $actions;
    }

    /**
     * Construye un DataProvider con el modelo search del controlador.
     * 
     * @return \DataProvider
     */
    public function getDataProvider()
    {
        $modelIntance = new $this->searchModel;
        return $modelIntance->search(Yii::$app->request->queryParams);
    }
}