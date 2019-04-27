<?php

namespace kevocode\tools\helpers;

use Yii;

/**
 * Configuraciones globales para el sistema, entre widgets, columnas y otros.
 *
 * @package kevocode
 * @subpackage tools\helpers
 * @category Utils
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class Configs
{
    /**
     * Definición de configuración global del aplicativo.
     */
    public static function defineDefaultWidgetConfig()
    {
        Yii::$container->set(\yii\grid\GridView::class, self::getConfigGridView());
    }

    /**
     * Define la configuración por defecto para el widget de GridView
     * 
     * @return array
     */
    public static function getConfigGridView()
    {
        return [
        ];
    }
}