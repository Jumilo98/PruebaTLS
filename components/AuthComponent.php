<?php
namespace app\components;

use Yii;
use yii\web\UnauthorizedHttpException;
use app\models\User;

class AuthComponent
{
    public static function authenticate()
    {
        try {
            // Obtener el token de autenticación
            $authHeader = Yii::$app->request->headers->get('Authorization');
            // Verificar si el token está presente
            if (!$authHeader || !preg_match('/^Bearer\s+(.*)$/', $authHeader, $matches)) {
                throw new UnauthorizedHttpException("Token de autenticación requerido.");
            }
            // Validar el token
            $token = $matches[1];
            // Decodificar el token
            $decodedToken = User::validateJwt($token);
            // Verificar si el token es válido
            if (!$decodedToken) {
                throw new UnauthorizedHttpException("Token inválido o expirado.");
            }
        } catch (UnauthorizedHttpException $e) {
            // Cambiar el formato de respuesta a JSON
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->statusCode = 401; // HTTP 401 Unauthorized
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}
