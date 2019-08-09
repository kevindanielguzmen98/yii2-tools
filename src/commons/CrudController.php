<?php

namespace kevocode\tools\commons;

use Yii;
use kevocode\tools\helpers\Configs;
use yii\filters\VerbFilter;
use yii\web\View;
use yii\helpers\ArrayHelper;

/**
 * Extención de funcionalidad de controladores para que se realice acciones de un CRUD básico.
 *
 * @package kevocode
 * @subpackage tools\commons
 * @category Controllers
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class CrudController extends \kevocode\tools\commons\Controller
{
    /**
     * Clase tipo modelo que se utilizará para la creación o modificación.
     *
     * @var string
     */
    public $baseModel = null;

    /**
     * Clase tipo modelo que se utilizará para realizar los filtros de búsqueda.
     *
     * @var string
     */
    public $searchModel = null;

    /**
     * Clase que deteminará con que UI se contruirá las vistas que lo necesiten
     * 
     * @var string
     */
    public $uiClass = \kevocode\tools\helpers\UI::class;

    /**
     * Evento inicializador de la clase
     */
    public function init()
    {
        parent::init();
        Configs::defineDefaultWidgetConfig();
        $this->getView()->on(View::EVENT_AFTER_RENDER, [$this, 'onAfterRender']);
    }

    /**
     * Configuración de los comportamientos de la aplicación
     * 
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET', 'POST'],
                    'view' => ['GET'],
                    'create' => ['GET', 'POST'],
                    'update' => ['GET', 'POST'],
                    'delete' => ['POST'],
                    'restore' => ['POST']
                ],
            ],
        ];
    }

    /**
     * Implementación de acciones para el controlador
     * 
     * @return array
     */
    public function actions()
    {
        return [
            'index' => \kevocode\tools\commons\actions\IndexAction::class,
            'view' => \kevocode\tools\commons\actions\ViewActions::class,
            'create' => \kevocode\tools\commons\actions\CreateAction::class,
            'update' => \kevocode\tools\commons\actions\UpdateAction::class,
            'delete' => \kevocode\tools\commons\actions\DeleteAction::class,
            'restore' => \kevocode\tools\commons\actions\RestoreAction::class
        ];
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return \yii\db\ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = $this->baseModel::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Evento manejador para antes de renderizar.
     * 
     * @param 
     */
    public function onAfterRender($event) {
        $event->sender->params['breadcrumbs'] = array_merge([
            ['label' => Yii::t('app', 'Configurations'), 'url' => '#']
        ], $event->sender->params['breadcrumbs']);
        $this->getView()->off(View::EVENT_AFTER_RENDER, [$this, 'onAfterRender']);
    }
}