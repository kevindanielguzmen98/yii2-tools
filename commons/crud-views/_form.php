<?php

use yii\widgets\ActiveForm;
use app\helpers\UI;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'id' => 'create-update-form-' . $id
]); ?>
    <div class="row">
        <?php foreach ($model::formColumns() as $column): ?>
            <?php if (!isset($column['onlyInSearch']) || (isset($column['onlyInSearch']) && !$column['onlyInSearch'])): ?>
                <?= UI::renderField($form, $model, $column) ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <div class="col-12 col-md-12">
            <?= UI::buttonIcon(Yii::t('app', 'Save'), 'fas fa-save', [
                'class' => 'btn btn-primary',
                'type' => 'submit',
                'form' => 'create-update-form-' . $id
            ]) ?>
            <?= UI::a(UI::icon('fas fa-times') .' ' . Yii::t('app', 'Cancel'), ['index'], [
                'class' => 'btn btn-secondary'
            ]) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
