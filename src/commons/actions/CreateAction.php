<?php

namespace kevocode\tools\commons\actions;

use Yii;

/**
 * Acción para mostrar formulario de creación y almacenar esta información.
 *
 * @package kevocode
 * @subpackage tools\commons\actions
 * @category Actions
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class CreateAction extends BaseAction
{
    /**
     * Evento de ejecución de la acción
     * 
     * @return mixed
     */
    public function run()
    {
        $model = new $this->controller->baseModel();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('default', [
                'type' => 'success',
                'heading' => Yii::t('app', 'Success'),
                'text' => Yii::t('app', 'The resource has been successfully created')
            ]);
            return $this->controller->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }       
}