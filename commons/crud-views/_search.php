<?php

use yii\widgets\ActiveForm;
use app\helpers\UI;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $model app\models\search\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'search-form-' . $id
    ]); ?>
        <div class="row">
            <?php foreach ($model->formColumns() as $column): ?>
                <?= UI::renderField($form, $model, $column) ?>
            <?php endforeach; ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>