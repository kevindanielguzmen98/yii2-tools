<?php

namespace kevocode\tools\helpers;

use Yii;
use kartik\grid\GridView;

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
        // Yii::$container->set(\yii\grid\GridView::class, self::getConfigGridView());
        Yii::$container->set(\kartik\dynagrid\DynaGrid::class, self::getConfigDynagrid());
    }

    /**
     * Define la configuración por defecto para el widget de Dynagrid
     * 
     * @return array
     */
    public static function getConfigDynagrid()
    {
        return [
            'options' => []
        ];
    }

    /**
     * Retorna arreglo con los estados de los registros
     * 
     * @param $model Instancia del modelo
     * @return array
     */
    public static function getStatusStates($model)
    {
        return [
            $model::STATUS_ACTIVE => Yii::t('app', 'Active'),
            $model::STATUS_INACTIVE => Yii::t('app', 'Inactive')
        ];
    }
}