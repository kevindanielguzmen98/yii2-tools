<?php

namespace kevocode\tools\auth\models;

use Yii;

/**
 * User provee todas las funciones necesarias para la autenticación de usuarios y su administración.
 *
 * @package kevocode
 * @subpackage tools\auth\models
 * @category models
 *
 * @author Kevin Daniel Guzman Delgadillo <kevindanielguzmen98@gmail.com>
 * @version 0.0.1
 * @since 0.0.0
 */
class User extends \mdm\admin\models\User
{
    const CREATED_AT_COLUMN = 'created_at';
    const UPDATED_AT_COLUMN = 'updated_at';
    const CREATED_BY_COLUMN = 'created_by';
    const UPDATED_BY_COLUMN = 'updated_by';
    const STATUS_COLUMN = 'record_status';
    const STATUS_ACTIVE = 'A';
    const STATUS_INACTIVE = 'I';
    const APPLICATION_USER_ID = 1;

    /**
     * Configuración de los comportamientos del modelo.
     * 
     * @return
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * Definición de reglas de validación de los campos del modelo.
     * 
     * @return array
     */
    public function rules()
    {
        return [
            [static::STATUS_COLUMN, 'in', 'range' => [static::STATUS_ACTIVE, static::STATUS_INACTIVE]],
        ];
    }

    /**
     * Búsca un usuario por su llave primaria.
     * 
     * @return array
     */
    public static function findIdentity($id)
    {
        return static::findOne([static::primaryKey()[0] => $id, static::STATUS_COLUMN => static::STATUS_ACTIVE]);
    }

    /**
     * Búsca un usuario por su username.
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, static::STATUS_COLUMN => static::STATUS_ACTIVE]);
    }

    /**
     * Búsca un usuario por su password_reset_token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            static::STATUS_COLUMN => static::STATUS_ACTIVE,
        ]);
    }

    /**
     * Búsca el usuario por token de acceso
     * 
     * @return User
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if (!empty($token)) {
            return self::findOne([
                'token' => $token,
                self::STATUS_COLUMN => self::STATUS_ACTIVE
            ]);
        }
    }

    /**
     * Asigna un token de acceso al usuario.
     * 
     * @return boolean
     */
    public function setAccessToken()
    {
        $this->token = static::generateAccessToken();
        return $this->save(false);
    }

    /**
     * Genera un token de accesos único.
     * 
     * @return string
     */
    public static function generateAccessToken()
    {
        $token = Yii::$app->security->generateRandomString(34);
        while (!is_null(self::findOne([ 'token' => $token ]))) {
            $token = Yii::$app->security->generateRandomString(34);
        }
        return $token;
    }
}