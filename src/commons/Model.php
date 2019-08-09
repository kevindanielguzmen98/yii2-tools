<?php

namespace kevocode\tools\commons;

use Yii;
use kartik\grid\GridView;
use kevocode\tools\helpers\Configs;
use app\helpers\ArrayHelper;

/**
 * Extención de funcionalidad para modelo tipo ActiveRecord.
 *
 * @package kevocode
 * @subpackage tools\commons
 * @category Models
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class Model extends \yii\db\ActiveRecord
{
    const CREATED_AT_COLUMN = 'created_at';
    const UPDATED_AT_COLUMN = 'updated_at';
    const CREATED_BY_COLUMN = 'created_by';
    const UPDATED_BY_COLUMN = 'updated_by';
    const STATUS_COLUMN = 'record_status';
    const STATUS_ACTIVE = 'A';
    const STATUS_INACTIVE = 'I';
    const APPLICATION_USER_ID = 1;

    /**
     * Determina si se utilizarán las columnas de estado
     */
    public $useStatusColumn = true;

    /**
     * Definición de evento de inicialización del modelo
     */
    public function init()
    {
        parent::init();
        if ($this->useStatusColumn) {
            $this->{static::STATUS_COLUMN} = static::STATUS_ACTIVE;
        }
    }

    /**
     * Define la columna que se mostrará en los formularios para identificar el recurso
     * 
     * @return string
     */
    public static function columnInCrud()
    {
        return static::primaryKey()[0];
    }

    /**
     * Define el nombre que se mostrará en el CRUD.
     * 
     * @return string
     */
    public static function crudTitle()
    {
        return Yii::t('app', ucfirst(str_replace('_', ' ', static::tableName())));
    }

    /**
     * Define las columnas que serán mostradas en el admin por defecto
     * 
     * @return array
     */
    public static function gridColumns()
    {
        $instance = new static;
        return array_filter($instance->attributes(), function ($value) use ($instance) {
            $commonColumns = [$instance::primaryKey()[0], static::STATUS_COLUMN, static::CREATED_AT_COLUMN, static::CREATED_BY_COLUMN, static::UPDATED_AT_COLUMN, static::UPDATED_BY_COLUMN];
            return !in_array($value, $commonColumns);
        });
    }

    /**
     * Define las columnas que serán mostradas en los formularios
     * 
     * @return array
     */
    public static function formColumns()
    {
        $instance = new static;
        return array_map(function ($value) use ($instance) {
            $auditColumns = [$instance::primaryKey()[0], $instance::STATUS_COLUMN, $instance::CREATED_AT_COLUMN, $instance::CREATED_BY_COLUMN, $instance::UPDATED_AT_COLUMN, $instance::UPDATED_BY_COLUMN];
            return [
                'name' => $value,
                'containerOptions' => ['class' => 'col-12 col-md-6'],
                'in' => (!in_array($value, $auditColumns) ? ['C', 'U', 'S'] : [])
            ];
        }, $instance->attributes());
    }
}