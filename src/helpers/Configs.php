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

    /**
     * Retorna un arreglo de configuración para el controller map de la aplicación con todas las configuraciones
     * necesarias para hacer funcionar los CRUD's
     * 
     * @param array $config Configuración para la creación del arreglo controllerMap:
     * ```
     * [
     *     'controllerClass' => '' // Clase que será utilizada como clase base de los controladores
     *     'nsModels' => '' // Espacio de nombres para los modelos base
     *     'nsSearchModels' => '' // Espacio de nombres para los modelos de búsqueda
     *     'tables' => [
     *         'alias' => 'table_name' // Alias es como será la ruta del controlador y el nombre de la tabla que administrará como valor
     *     ] || [
     *         'table_name' // Solo el nombre de la tabla sin alias
     *     ] || [
     *         [
     *             'table' => 'table_name',
     *             'config' => [] // Aditional configuration for controller
     *         ] 
     *     ]
     * ]
     * ```
     * @return array
     */
    public static function getControllerMap($config)
    {
        $controllerClass = $config['controllerClass'];
        $nsModels = $config['nsModels'];
        $nsSearchModels = $config['nsSearchModels'];
        $tables = $config['tables'];
        $controllerMap = [];
        foreach ($tables as $key => $value) {
            $alias = $key;
            $tableName = $value;
            $aditionalConfig = [];
            if (is_int($key)) {
                $alias = str_replace('_', '-', $value);
            }
            if (is_array($tableName)) {
                $tableName = $tableName['table'];
                $aditionalConfig = isset($tableName['config']) ? $tableName['config'] : [];
            }
            $modelName = str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $tableName)));
            $controllerMap[$alias] = array_merge([
                'class' => $controllerClass,
                'baseModel' => $nsModels . '\\' . $modelName,
                'searchModel' => $nsSearchModels . '\\' . $modelName
            ], $aditionalConfig);
        }
        return $controllerMap;
    }
}