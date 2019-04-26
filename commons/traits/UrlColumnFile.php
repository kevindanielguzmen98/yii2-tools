<?php

namespace kevocode\tools\commons\traits;

use Yii;
use yii\helpers\Url;

/**
 * Característica para la optención de url pública a un archivo.
 *
 * @package kevocode
 * @subpackage tools\commons\traits
 * @category Traits
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 1.0.0
 */
trait UrlColumnFile
{
    /**
     * Retorna la url completa hacia el archivo según la columna pasada.
     * 
     * @param string $fileName Nombre de archivo
     * @param string $uriFileDefault Uri completa a archivo que se tomará por defecto en caso de no estar.
     * @return string
     */
    public function getUrlFile($fileName, $uriFileDefault = null)
    {
        $uniqueColumnFolder = $this->getUniqueColumnFolder();
        $baseUri = $this->getBaseUri();
        $uri = trim($baseUri, '/') .'/'. $this->{$uniqueColumnFolder} .'/'. $fileName;
        $path = Yii::getAlias('@webroot/'. $uri);
        if (!empty($uriFileDefault) && !file_exists($path)) {
            $uri = $uriFileDefault;
        }
        return Url::toRoute('@web/'. $uri);
    }
}