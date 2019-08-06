<?php

namespace kevocode\tools\commons\actions;

use Yii;

/**
 * Acción para mostrar formulario de actualización y almacenar esta información.
 *
 * @package kevocode
 * @subpackage tools\commons\actions
 * @category Actions
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class UpdateAction extends BaseAction
{
    /**
     * Evento de ejecución de la acción
     * 
     * @return mixed
     */
    public function run($id)
    {
        $model = $this->controller->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('default', [
                'type' => 'success',
                'heading' => Yii::t('app', 'Updated'),
                'text' => Yii::t('app', 'The resource has been updated successfully')
            ]);
            return $this->redirect(['index']);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }       
}