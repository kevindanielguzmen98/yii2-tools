<?php

namespace kevocode\tools\commons;

use Yii;
use yii\helpers\Html;

/**
 * Extención de funcionalidad para renderizar las columnas en los CRUD.
 *
 * @package kevocode
 * @subpackage tools\commons
 * @category Tools
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class ActionColumn extends \kartik\grid\ActionColumn
{
    /**
     * Opciones html para el botón de restauración
     * 
     * @var array
     */
    public $restoreOptions = [];

    /**
     * Definición de sentencias para deteminar si un botón es visible o no lo es
     * 
     * @var array
     */
    public $visibleButtons = [];

    /**
     * Método de inicialización de la clase
     */
    public function init()
    {
        parent::init();
        $this->visibleButtons = array_merge($this->visibleButtons, [
            'update' => function ($model, $key, $index) {
                return ($model->{$model::STATUS_COLUMN} == $model::STATUS_ACTIVE);
            },
            'delete' => function ($model, $key, $index) {
                return ($model->{$model::STATUS_COLUMN} == $model::STATUS_ACTIVE);
            },
            'restore' => function ($model, $key, $index) {
                return !($model->{$model::STATUS_COLUMN} == $model::STATUS_ACTIVE);
            }
        ]);
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    protected function initDefaultButtons()
    {
        $isBs4 = $this->grid->isBs4();
        $this->setDefaultButton('view', Yii::t('app', 'View'), 'fas fa-eye');
        $this->setDefaultButton('update', Yii::t('app', 'Edit'), 'fas fa-pen');
        $this->setDefaultButton('delete', Yii::t('app', 'Delete'), 'fas fa-trash');
        $this->setDefaultButton('restore', Yii::t('app', 'Restore'), 'fas fa-redo');
    }

    /**
     * Sets a default button configuration based on the button name (bit different than [[initDefaultButton]] method)
     *
     * @param string $name button name as written in the [[template]]
     * @param string $title the title of the button
     * @param string $icon the meaningful glyphicon suffix name for the button
     * @throws InvalidConfigException
     */
    protected function setDefaultButton($name, $title, $icon)
    {
        $isBs4 = $this->grid->isBs4();
        if (isset($this->buttons[$name])) {
            return;
        }
        $this->buttons[$name] = function ($url) use ($name, $title, $icon, $isBs4) {
            $opts = "{$name}Options";
            $options = ['title' => $title, 'aria-label' => $title, 'data-pjax' => '0'];
            if ($name === 'delete') {
                $options['data-method'] = 'post';
                $options['data-confirm'] = Yii::t('app', 'Are you sure to delete this record?');
            }
            if ($name === 'restore') {
                $options['data-method'] = 'post';
                $options['data-confirm'] = Yii::t('app', 'Are you sure to restore this record?');
            }
            $options = array_replace_recursive($options, $this->buttonOptions, $this->$opts);
            $label = $this->renderLabel($options, $title, ['class' => $this->grid->getDefaultIconPrefix() . $icon]);
            $link = Html::a($label, $url, $options);
            if ($this->_isDropdown) {
                $options['tabindex'] = '-1';
                return $isBs4 ? $link : "<li>{$link}</li>\n";
            } else {
                return $link;
            }
        };
    }    
}