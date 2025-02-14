<?php

namespace app\models;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\base\Model;
use yii\web\IdentityInterface;
use Exception;
use Yii;

class User extends Model implements IdentityInterface
{
    public $_id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        "admin" => [
            "username" => "admin",
            "password" => "admin123"
        ]
    ];
     // Busca un usuario por su nombre de usuario.
    public static function findByUsername($username)
    {
        return isset(self::$users[$username]) ? new static(self::$users[$username]) : null;
    }
    // Valida la contraseña.
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
    //Genera un JWT Token
    public function generateJwt()
    {
        // Clave secreta para firmar el token
        $key = Yii::$app->params['jwtSecretKey'];
        // Tiempo de expiración del token
        $issuedAt = time();
        $expirationTime = $issuedAt + (30 * 60); // 30 minutos
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'username' => $this->username,
        ];
        // Retorna el token JWT
        return JWT::encode($payload, $key, 'HS256');
    }
    //Verifica un token JWT
    public static function validateJwt($token)
    {
        try {
            // Clave secreta para firmar el token
            $key = Yii::$app->params['jwtSecretKey'];
            // Decodifica el token
            return JWT::decode($token, new Key($key, 'HS256'));
        } catch (Exception $e) {
            return null;
        }
    }
    // Métodos requeridos por IdentityInterface - Obtiene la identidad del usuario
    public static function findIdentity($id) 
    { 
        return null;     
    }
    // Obtiene la identidad del usuario
    public static function findIdentityByAccessToken($token, $type = null) 
    { 
        return null; 
    }
    // Obtiene el ID del usuario
    public function getId() 
    { 
        return $this->_id; 
    }
    // Obtiene la clave de autenticación
    public function getAuthKey() 
    { 
        return $this->authKey; 
    }
    // Valida la clave de autenticación
    public function validateAuthKey($authKey) 
    { 
        return $this->authKey === $authKey; 
    }
}
