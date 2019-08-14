<?php

namespace kevocode\tools\widgets;
/**
 * Sobreescritura de lase ActiveForm de yii.
 * 
 * @package kevocode\tools\widgets
 * 
 * @author Kevin Daniel Guzmán Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 1.0.0
 * @since 0.0.1
 */
class ActiveForm extends \yii\bootstrap4\ActiveForm
{
    public $fieldClass = \kevocode\tools\widgets\ActiveField::class;
}