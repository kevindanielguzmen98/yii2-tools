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
    public static function dynagridComponent($title = 'any', $config = [])
    {
        $id = Inflector::slug($title);
        $config = ArrayHelper::merge([
            'gridOptions' => [
                'id' => 'grid-'. $id,
                'pjax' => true,
                'toolbar' =>  [
                    [
                        'content' =>
                            UI::buttonIcon('', 'fas fa-plus', [
                                'class' => 'btn btn-success',
                                'title' => Yii::t('app', 'Create'),
                                'onclick' => 'alert("This will launch the book creation form.\n\nDisabled for this demo!");'
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
        ], $config);
        return DynaGrid::widget($config);
    }
}