<?php

namespace kevocode\tools\commons\traits;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * Trait para la implementación de los comportamientos para el manejo de las columnas de administración.
 * 
 * @property boolean $useAdminColumns Define si se usarán o no 
 * los comportamientos para las columnas de administración.
 * 
 * @package kevocode
 * @subpackage tools\commons\traits
 * @category traits
 * 
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 0.0.0
 */
trait ManagementAdmColumns
{
    public $useAdminColumns = true;
    
    /**
     * Define los comportamientos por defecto para el modelo.
     * 
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if ($this->useAdminColumns) {
            $behaviors = array_merge($behaviors, [
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => static::CREATED_AT_COLUMN,
                    'updatedAtAttribute' => static::UPDATED_AT_COLUMN,
                    'value' => new Expression('NOW()'),
                ],
                [
                    'class' => BlameableBehavior::className(),
                    'createdByAttribute' => static::CREATED_BY_COLUMN,
                    'updatedByAttribute' => static::UPDATED_BY_COLUMN,
                    'defaultValue' => static::APPLICATION_USER_ID
                ]
            ]);
        }
        return $behaviors;
    }
}