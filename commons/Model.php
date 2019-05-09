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