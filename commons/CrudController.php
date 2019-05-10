<?php

namespace kevocode\tools\commons;

use Yii;
use kevocode\tools\helpers\Configs;

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
     * Evento inicializador de la clase
     */
    public function init()
    {
        parent::init();
        Configs::defineDefaultWidgetConfig();
    }
}