<?php

namespace kevocode\tools\commons;

/**
 * Extención de la clase DynamicModel para implementar la carga de reglas en una sola linea.
 *
 * @package kevocode
 * @subpackage tools\commons
 * @category tools
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 0.0.0
 */
class DynamicModel extends \yii\base\DynamicModel
{
    /**
     * Crear una intancia de la clase con los attributos y las reglas ya pasadas y listo para utilizar.
     * 
     * @param array $attributes Atributos a ser validados.
     * @param array $rules Reglas de validación en forma de arreglo.
     * @return DynamicModel
     */
    public static function withRules($attributes, $rules = [])
    {
        $instance = new self($attributes);
        foreach ($rules as $rule) {
            $options = [];
            if (count($rule) > 2) {
                $options = $rule;
                unset($options[0], $options[1]);
            }
            $instance->addRule($rule[0], $rule[1], $options);
        }
        return $instance;
    }
}