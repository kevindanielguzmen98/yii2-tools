<?php

namespace kevocode\tools\commons\actions;

use Yii;

/**
 * Acción para la visualización detallada de un registro.
 *
 * @package kevocode
 * @subpackage tools\commons\actions
 * @category Actions
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class ViewActions extends BaseAction
{
    /**
     * Evento de ejecución de la acción
     * 
     * @return mixed
     */
    public function run($id)
    {
        return $this->controller->render($this->getViewRoute('view'), [
            'model' => $this->controller->findModel($id),
        ]);
    }    
}