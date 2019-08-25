<?php

namespace kevocode\tools\commons;

use Yii;
use kartik\grid\GridView;
use kevocode\tools\helpers\Configs;
use app\helpers\ArrayHelper;
use yii\helpers\Inflector;

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
        $relationshipList = static::getRelationsHasOne();
        $instance = new static;
        $columnsConfig = array_filter($instance->attributes(), function ($value) use ($instance) {
            $commonColumns = [$instance::primaryKey()[0], static::STATUS_COLUMN, static::CREATED_AT_COLUMN, static::CREATED_BY_COLUMN, static::UPDATED_AT_COLUMN, static::UPDATED_BY_COLUMN];
            return !in_array($value, $commonColumns);
        });
        return array_map(function ($value) use ($relationshipList) {
            $isRelated = array_filter($relationshipList, function ($item) use ($value) {
                return $item['local_column'] == $value;
            });
            if (!empty($isRelated)) {
                $isRelated = end($isRelated);
                $className = '\app\models\\' . Inflector::pluralize(Inflector::classify($isRelated['referenced_table']));
                $value = [
                    'attribute' => $value,
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => $className::getData(),
                    'filterWidgetOptions' => [
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'options' => [
                            'placeholder' => Yii::t('app', 'Empty')
                        ]
                    ],
                    'filterInputOptions' => ['id' => $value.'-gridcolumn']
                ];
            }
            return $value;
        }, $columnsConfig);
    }

    /**
     * Define las columnas que serán mostradas en los formularios
     * 
     * @return array
     */
    public static function formColumns()
    {
        $relationshipList = static::getRelationsHasOne();
        $instance = new static;
        return array_map(function ($value) use ($instance, $relationshipList) {
            $auditColumns = [$instance::primaryKey()[0], $instance::STATUS_COLUMN, $instance::CREATED_AT_COLUMN, $instance::CREATED_BY_COLUMN, $instance::UPDATED_AT_COLUMN, $instance::UPDATED_BY_COLUMN];
            $isRelated = array_filter($relationshipList, function ($item) use ($value) {
                return $item['local_column'] == $value;
            });
            $columnConfig = [
                'name' => $value,
                'containerOptions' => ['class' => 'col-12 col-md-6'],
                'in' => (!in_array($value, $auditColumns) ? ['C', 'U', 'S'] : []),
                'options' => ['help' => '', 'popover' => $instance->getHelp($value)]
            ];
            if (!empty($isRelated)) {
                $isRelated = end($isRelated);
                $className = '\app\models\\' . Inflector::pluralize(Inflector::classify($isRelated['referenced_table']));
                $columnConfig = array_merge($columnConfig, [
                    'widget' => [
                        'class' => \kartik\select2\Select2::class,
                        [
                            'data' => $className::getData(),
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                            'options' => [
                                'placeholder' => Yii::t('app', 'Empty')
                            ]
                        ]
                    ]
                ]);
            }
            return $columnConfig;
        }, $instance->attributes());
    }

    /**
     * Retorna las ayudas para los formularios
     * 
     * @param string $columnName
     * @return string
     */
    public function getHelp($columnName)
    {
        $helps = [];
        foreach ($this->attributes() as $value) {
            $helps[$value] = Yii::t('app', ucwords(str_replace('_', ' ', $value)));
        }
        return isset($helps[$columnName]) ? $helps[$columnName] : null;
    }

    /**
     * Define el nombre de la columna secundario que será utilizada para identificar el recurso como:
     * name, lastname, email, etc
     * 
     * @return string
     */
    public static function secondaryKey()
    {
        $primaryKey = static::primaryKey();
        $othersColumns = array_filter(static::attributes(), function ($item) use ($primaryKey) {
            return !in_array($item, $primaryKey);
        });
        return reset($othersColumns);
    }

    /**
     * Retorna los datos mapeados en forma de lista
     * 
     * @return array
     */
    public static function getData()
    {
        return ArrayHelper::map(static::findAll([
            static::STATUS_COLUMN => static::STATUS_ACTIVE
        ]), static::primaryKey(), static::secondaryKey());
    }

    /**
     * Retorna las relaciones uno a muchos que posee la tabla actual
     * 
     * @return array
     */
    public static function getRelationsHasOne()
    {
        $relationshipList = self::getDb()->createCommand("SELECT fk.conname as constraint_name,
                fk.conrelid::regclass as local_table,
                a.attname as local_column,
                fk.confrelid::regclass as referenced_table,
                af.attname as referenced_column
            FROM (
                SELECT
                    conname,
                    conrelid,
                    confrelid,
                    unnest(conkey)  AS conkey,
                    unnest(confkey) AS confkey
                FROM pg_constraint
                WHERE contype = 'f'
                ) fk
            JOIN pg_attribute af ON af.attnum = fk.confkey AND af.attrelid = fk.confrelid
            JOIN pg_attribute a ON a.attnum = conkey AND a.attrelid = fk.conrelid
            where fk.conrelid::regclass::text=:table
            ORDER BY fk.confrelid, fk.conname;
        ", [':table' => static::tableName()])->queryAll();
        return $relationshipList;
    }
}