<?php

namespace kevocode\tools\commons;

use Yii;

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
     * Definición de evento de inicialización del modelo
     */
    public function init()
    {
        parent::init();
        $this->{static::STATUS_COLUMN} = static::STATUS_ACTIVE;
    }

    /**
     * Define las columnas que serán mostradas en el admin por defecto
     * 
     * @return array
     */
    public static function gridColumns()
    {
        $instance = new static;
        return $instance->attributes();
    }

    /**
     * Define las columnas que serán mostradas en los formularios
     * 
     * @return array
     */
    public static function formColumns()
    {
        $instance = new static;
        return $instance->attributes();
    }    
}