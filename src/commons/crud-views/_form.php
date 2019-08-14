<?php

use kevocode\tools\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */
/* @var $form kevocode\tools\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'id' => 'create-update-form-' . $id
]); ?>
    <div class="row">
        <?php foreach ($model::formColumns() as $column): ?>
            <?php if (in_array($type, $column['in'])): ?>
                <?= Yii::$app->controller->uiClass::renderField($form, $model, $column) ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <div class="col-12 col-md-12">
            <?= Yii::$app->controller->uiClass::buttonIcon(Yii::t('app', 'Save'), 'fas fa-save', [
                'class' => 'btn btn-primary',
                'type' => 'submit',
                'form' => 'create-update-form-' . $id
            ]) ?>
            <?= Yii::$app->controller->uiClass::a(Yii::$app->controller->uiClass::icon('fas fa-times') .' ' . Yii::t('app', 'Cancel'), ['index'], [
                'class' => 'btn btn-secondary'
            ]) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
