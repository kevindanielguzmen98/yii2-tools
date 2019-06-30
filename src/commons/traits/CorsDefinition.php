<?php

namespace kevocode\tools\commons\traits;

/**
 * Trait para la implementación de cabeceras CORS.
 * 
 * @package kevocode
 * @subpackage tools\commons\traits
 * @category traits
 * 
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 0.0.0
 */
trait CorsDefinition
{
    /**
     * Adiere a las cabeceras de la respuesta los CORS.
     */
    private function setCors()
    {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, OPTIONS");
            }
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }
            exit(0);
        }
    }

    /**
     * Definición de evento antes de lanzar cualquier acción de los controladores
     * 
     * @return boolean.
     */
    public function beforeAction($action)
    {
        $this->setCors();
        return parent::beforeAction($action);
    }
}