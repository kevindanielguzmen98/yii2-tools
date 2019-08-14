<?php

namespace kevocode\tools\widgets;

use Yii;
use kevocode\tools\helpers\UI;

/**
 * Sobreescritura de lase ActiveField de yii.
 *
 * @package kevocode\tools\widgets
 *
 * @author Kevin Daniel Guzmán Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 1.0.0
 * @since 0.0.1
 */
class ActiveField extends \yii\bootstrap4\ActiveField
{
    public $help = null;
    public $popover;
    public $errorOptions = ['class' => 'invalid-feedback'];

    /**
     * {@inheritdoc}
     */
    public function label($label = null, $options = [])
    {
        if ($label === false) {
            $this->parts['{label}'] = '';
            return $this;
        }

        $options = array_merge($this->labelOptions, $options);
        if ($label !== null) {
            $options['label'] = $label;
        }
        $help = '';
        if (!is_null($this->help)) {
            $help .=
                '&nbsp;&nbsp;&nbsp;&nbsp;' .
                UI::tag('span', $this->help, [
                    'style' => 'color:#BFBFBF'
                ]);
            if (!empty($this->popover)) {
                $help .=
                    '&nbsp;&nbsp;' .
                    UI::tag('span', '', [
                        'class' => 'fa fa-info-circle',
                        'data-title' => Yii::t('app', 'Help'),
                        'data-placement' => 'top',
                        'data-content' => $this->popover,
                        'data-trigger' => 'hover',
                        'data-toggle' => 'popover'
                    ]);
            }
        }
        $this->parts['{label}'] =
            UI::activeLabel($this->model, $this->attribute, $options) . $help;

        return $this;
    }
}
