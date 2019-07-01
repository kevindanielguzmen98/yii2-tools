<?php

namespace kevocode\tools\commons\actions;

use Yii;

/**
 * Acción base para los CRUD base.
 *
 * @package kevocode
 * @subpackage tools\commons\actions
 * @category Actions
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class BaseAction extends \yii\base\Action
{
    /**
     * URI de la vista base en donde buscará la vista a renderizar.
     * 
     * @var string
     */
    public $baseViewPath = '@vendor/kevocode/yii2-tools/src/commons/crud-views';

    /**
     * Evento para la validación del acceso.
     * 
     * @var mixed
     */
    public $checkAccess;

    /**
     * Método inicializador de la acción
     */
    public function init()
    {
        parent::init();
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }
    }

    /**
     * Renderiza una vista validando que exista el archivo en las vistas de la aplicación,
     * si estas no existen renderiza las por defecto
     * 
     * @return string
     */
    public function getViewRoute($viewName)
    {
        $extension = explode('.', $viewName);
        $extension = '.'. (isset($extension[1]) ? $extension[1] : $this->controller->view->defaultExtension); 
        $view = $this->controller->getViewPath() . DIRECTORY_SEPARATOR . $viewName . $extension;
        if (!file_exists($view)) {
            $view = $this->baseViewPath . DIRECTORY_SEPARATOR . $viewName . $extension;
        } else {
            $view = $viewName;
        }
        return $view;
    }
}