<?php

use yii\helpers\Html;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */

$this->title = Yii::t('app', 'Update {title}: {name}', [
    'title' => strtolower($model::crudTitle()),
    'name' => $model->{$model::columnInCrud()},
]);
$this->params['breadcrumbs'][] = ['label' => $model::crudTitle(), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$id = Inflector::slug($model::crudTitle());
?>
<div class="<?= $id ?>-update card">
    <div class="card-body">
        <div class="d-flex align-items-start justify-content-between mb-3">
            <h4><?= Html::encode($this->title) ?></h4>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
            'id' => $id,
            'type' => 'U'
        ]) ?>
    </div>
</div>
