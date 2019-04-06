<?php

namespace kevocode\tools\rest;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use kevocode\tools\commons\traits\ManagementAdmColumns;

/**
 * Modelo del cual extienden los modelos para el módulo.
 * 
 * @property boolean $useAdminColumns Determina si se usará columnas de administración.
 * 
 * @package kevocode
 * @subpackage tools\rest
 * @category models
 * 
 * @author Kevin Daniel Guzmán Delgadillo <kevindanielguzmen98gmail.com>
 * @version 0.0.1
 * @since 0.0.0
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
}