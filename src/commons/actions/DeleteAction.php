<?php

namespace kevocode\tools\commons\actions;

use Yii;

/**
 * Acción para eliminar un recurso
 *
 * @package kevocode
 * @subpackage tools\commons\actions
 * @category Actions
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class DeleteAction extends BaseAction
{
    /**
     * Evento de ejecución de la acción
     * 
     * @return mixed
     */
    public function run($id)
    {
        $model = $this->controller->findModel($id);
        $model->{$this->controller->baseModel::STATUS_COLUMN} = $this->controller->baseModel::STATUS_INACTIVE;
        if ($model->save(false)) {
            Yii::$app->session->setFlash('default', [
                'type' => 'success',
                'heading' => Yii::t('app', 'Removed'),
                'text' => Yii::t('app', 'The resource has been successfully removed')
            ]);
        }
        return $this->controller->redirect(['index']);
    }       
}