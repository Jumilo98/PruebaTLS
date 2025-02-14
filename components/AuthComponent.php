<?php
namespace app\components;

use Yii;
use yii\web\UnauthorizedHttpException;
use yii\web\Response;
use app\models\User;

class AuthComponent
{
    public static function authenticate()
    {
        try {
            // Obtener el token de autenticación
            $authHeader = Yii::$app->request->headers->get('Authorization');
            // Lanzar una excepción si no se proporciona un token
            if (!$authHeader || !preg_match('/^Bearer\s+(.*)$/', $authHeader, $matches)) {
                throw new UnauthorizedHttpException("Token de autenticación requerido.");
            }
            // Validar el token
            $token = $matches[1];
            // Decodificar el token
            $decodedToken = User::validateJwt($token);
            // Lanzar una excepción si el token no es válido
            if (!$decodedToken) {
                throw new UnauthorizedHttpException("Token inválido");
            }
        } catch (UnauthorizedHttpException $e) {
            // Si la solicitud acepta JSON (comprobar los tipos de contenido aceptables)
            if (in_array('application/json', Yii::$app->request->getAcceptableContentTypes())) {
                // Configurar la respuesta como JSON
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->statusCode = 401; // HTTP 401 Unauthorized
                return [
                    'error' => $e->getMessage(),
                ];
            }
            // Si no es una solicitud que acepte JSON, seguir el comportamiento por defecto
            throw $e;
        }
    }    
}
