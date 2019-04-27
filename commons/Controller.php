<?php

namespace kevocode\tools\commons;

use Yii;
use kevocode\tools\helpers\Configs;

/**
 * Extención de funcionalidad de controladores.
 *
 * @package kevocode
 * @subpackage tools\commons\components
 * @category Controllers
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class Controller extends \yii\web\Controller
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