<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\Categories */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="<?= $id ?>-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'search-form-' . $id
    ]); ?>
        <div class="row">
            <?php foreach (array_merge($model->formColumns(), Yii::$app->controller->uiClass::getCommonFormFields($model)) as $column): ?>
                <?php if (in_array('S', $column['in'])): ?>
                    <?= Yii::$app->controller->uiClass::renderField($form, $model, $column) ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
