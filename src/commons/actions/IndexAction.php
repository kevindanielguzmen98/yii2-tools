<?php

namespace kevocode\tools\commons\actions;

use Yii;

/**
 * Extención de funcionalidad de acción para renderizar la vista principal de
 * administración de un CRUD.
 *
 * @package kevocode
 * @subpackage tools\commons\actions
 * @category Actions
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class IndexAction extends BaseAction
{
    /**
     * Evento de ejecución de la acción
     *
     * @return mixed
     */
    public function run()
    {
        $searchModel = new $this->controller->searchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}
