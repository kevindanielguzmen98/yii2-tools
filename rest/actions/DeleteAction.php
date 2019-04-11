<?php

namespace kevocode\tools\rest\actions;

use Yii;

/**
 * Acción para inactivar los recursos.
 *
 * @package kevocode
 * @subpackage tools\rest\actions
 * @category actions
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 0.0.0
 */
class DeleteAction extends \yii\rest\DeleteAction
{
    /**
     * Deletes a model.
     * @param mixed $id id of the model to be deleted.
     * @throws ServerErrorHttpException on failure.
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        $model->{$model::STATUS_COLUMN} = $model::STATUS_INACTIVE;
        if ($model->save() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }
        Yii::$app->getResponse()->setStatusCode(204);
    }
}