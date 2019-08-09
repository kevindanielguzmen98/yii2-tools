<?php

use kevocode\tools\helpers\UI;
use yii\bootstrap4\Modal;
use yii\helpers\Inflector;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Categories */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel::crudTitle();
$this->params['breadcrumbs'][] = $this->title;
$id = Inflector::camel2id($searchModel::tableName());
?>
<div id="<?= $id ?>-container" class="row">
    <div class="col-12 grid-margin">
        <?= Yii::$app->controller->uiClass::dynagridComponent($id, $this->title, [
            'columns' => $searchModel::gridColumns(),
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel
            ]
        ]) ?>
    </div>
</div>
<?php Modal::begin([
    'id' => 'search-modal-' . $id,
    'title' => Yii::t('app', 'Advanced search'),
    'size' => Modal::SIZE_DEFAULT,
    'footer' => Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary', 'form' => 'search-form-' . $id])
        . Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary', 'form' => 'search-form-' . $id])
]); ?>
<?= $this->render('_search', [
    'model' => $searchModel,
    'id' => $id
]) ?>
<?php Modal::end(); ?>