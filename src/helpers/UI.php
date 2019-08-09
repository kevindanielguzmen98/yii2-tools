<?php
namespace kevocode\tools\helpers;

use Yii;
use yii\helpers\Inflector;
use kartik\dynagrid\DynaGrid;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/**
 * Extención de funcionalidad de helper Html de yii para dar elementos comunes en los proyectos.
 *
 * @package kevocode
 * @subpackage tools\helpers
 * @category helpers
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
class UI extends \yii\helpers\Html
{
    /**
     * Retorna un botón un icono
     * 
     * @param string $label Label del botón
     * @param string $icon Cadena o arreglo con la clase del icono que se quiere utilizar o la configuración para el tag "i"
     * @param array $options Opciones para el botón
     */
    public static function buttonIcon($label = '', $icon = '', $options = [])
    {
        $icon = self::icon($icon);
        $content = $icon .' '. $label;
        return self::tag('button', $content, $options);
    }

    /**
     * Retorna un tag con el icono según los attributos pasados
     * 
     * @param array $options Opciones para el elemento o la clase directamente
     */
    public static function icon($options)
    {
        if (!is_array($options)) {
            $options = [
                'class' => $options
            ];
        }
        return self::tag('i', null, $options);
    }

    /**
     * Renderiza una columna según una configuración pasada en "options" con las siguiente estructura
     * ```
     * [
     *     'name' => 'nombre_columna',
     *     'type' => 'text' // nombre del método que se utilizará para renderizar
     *     'label' => 'Nombre columna',
     *     'items' => [],
     *     'options' => [],
     *     'fieldOptions' => [],
     *     'widget' => [],
     *     'containerOptions' => [] // Opciones para el contenedor esto puede ser la clase de las columnas
     * ]
     * ```
     * 
     * @param $form Instancia de formulario
     * @param $model Instancia de modelo
     * @param array $options
     * @return string
     */
    public static function renderField($form, $model, $options = [])
    {
        $field = null;
        if (!is_array($options)) {
            $options = ['name' => $options];
        }
        $options['options'] = ArrayHelper::getValue($options, 'options', []);
        $field = $form->field($model, $options['name'], $options['options']);
        if (isset($options['widget']) && !empty($options['widget'])) {
            $field = call_user_func_array([$field, 'widget'], $options['widget']);
        } else {
            if (isset($options['type']) && !empty($options['type'])) {
                $field = call_user_func_array([$field, $options['type']], $options['fieldOptions']);
            }
            if (isset($options['label'])) {
                $field->label($options['label']);
            }
        }
        $options['containerOptions'] = ArrayHelper::getValue($options, 'containerOptions', []);
        return Html::tag('div', $field, $options['containerOptions']);
    }

    /**
     * Renderizado de elemento Dynagrid en las vistas, con columnas y demás
     * 
     * @param string $title Título para el elemento
     * @param array $columns Columnas que se mostrarán
     * @param array $config Configuraciones para dynagrid
     * @return string
     */
    public static function dynagridComponent($id, $title, $config = [])
    {
        $config['columns'] = ArrayHelper::merge(
            $config['columns'],
            static::getCommonGridColumns($config['gridOptions']['filterModel'])
        );
        $config = ArrayHelper::merge($config, [
            'gridOptions' => [
                'id' => 'grid-'. $id,
                'pjax' => true,
                'toolbar' =>  [
                    [
                        'content' =>
                            UI::a(UI::icon('fas fa-plus'), ['create'], [
                                'class' => 'btn btn-success',
                                'title' => Yii::t('app', 'Create'),
                                'data' => ['pjax' => 0]
                            ])
                            . UI::buttonIcon('', 'fas fa-search', [
                                'class' => 'btn btn-primary',
                                'title' => Yii::t('app', 'Advanced search'),
                                'data' => [
                                    'toggle' => 'modal',
                                    'target' => '#search-modal-'. $id
                                ]
                            ])
                    ],
                    [
                        'content' => UI::a(UI::icon('fas fa-redo'), ['index'], [
                            'class' => 'btn btn-outline-secondary',
                            'title'=>Yii::t('app', 'Clear filters'),
                            'data-pjax' => 1,
                        ]). '{toggleData}{dynagrid}'
                    ]
                ],
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                ],
                'panelHeadingTemplate' => '<h4 class="mb-0">'. $title .'</h4>',
                'panelAfterTemplate' => '<div class="row"><div class="col-12">{summary}</div></div>',
                'options' => []
            ],
            'options' => [
                'id' => 'dynagrid-'. $id
            ]
        ]);
        return DynaGrid::widget($config);
    }

    /**
     * Devuelve un arreglo con las columnas comúnes que son necesarias en los grid
     * 
     * @return array
     */
    public static function getCommonGridColumns($model)
    {
        $userList = Yii::$app->user->identityClass::findAll([
            Yii::$app->user->identityClass::STATUS_COLUMN => Yii::$app->user->identityClass::STATUS_ACTIVE
        ]);
        return [
            [
                'attribute' => $model::STATUS_COLUMN,
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => Configs::getStatusStates($model),
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ],
                'filterInputOptions' => ['placeholder' => Yii::t('app', 'Empty'), 'id' => 'grid-' . $model::STATUS_COLUMN]
            ],
            [
                'attribute' => $model::CREATED_BY_COLUMN,
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($userList, Yii::$app->user->identityClass::primaryKey(), 'username'),
                'value' => function ($model) use ($userList) {
                    foreach ($userList as $user) {
                        if ($model->{$model::CREATED_BY_COLUMN} == $user->{$user::primaryKey()[0]}) {
                            return $user->username;
                        }
                    }
                },
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ],
                'filterInputOptions' => ['placeholder' => Yii::t('app', 'Empty'), 'id' => 'grid-' . $model::CREATED_BY_COLUMN],
                'visible' => false
            ],
            [
                'attribute' => $model::CREATED_AT_COLUMN,
                'filterType' => GridView::FILTER_DATETIME,
                'format' => 'datetime',
                'filterInputOptions' => [
                    'id' => 'grid-' . $model::CREATED_AT_COLUMN 
                ],
                'visible' => false
            ],
            [
                'attribute' => $model::UPDATED_BY_COLUMN,
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($userList, Yii::$app->user->identityClass::primaryKey(), 'username'),
                'value' => function ($model) use ($userList) {
                    foreach ($userList as $user) {
                        if ($model->{$model::UPDATED_BY_COLUMN} == $user->{$user::primaryKey()[0]}) {
                            return $user->username;
                        }
                    }
                },
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ],
                'filterInputOptions' => ['placeholder' => Yii::t('app', 'Empty'), 'id' => 'grid-' . $model::UPDATED_BY_COLUMN],
                'visible' => false
            ],
            [
                'attribute' => $model::UPDATED_AT_COLUMN,
                'filterType' => GridView::FILTER_DATETIME,
                'format' => 'datetime',
                'filterInputOptions' => [
                    'id' => 'grid-' . $model::UPDATED_AT_COLUMN
                ],
                'visible' => false
            ],
            [
                'class' => \app\components\ActionColumn::class,
                'template' => '{update} {delete} {restore}'
            ]
        ];
    }

    /**
     * Definición de columna comunes para renderizar
     * 
     * @return array
     */
    public static function commonColumns()
    {
        return [
            'record_status' => [
                'name' => 'record_status',
                'widget' => [
                    'class' => \kartik\select2\Select2::class,
                    ''
                ]
            ]
        ];
    }
}