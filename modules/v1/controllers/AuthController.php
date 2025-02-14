<?php

namespace app\modules\v1\controllers;

use app\models\User;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use Yii;

class AuthController extends Controller
{
    // Se establece el formato de respuesta a JSON.
    public function behaviors()
    {
        $behaviors = parent::behaviors();
         // Configurar la respuesta como JSON
         $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        // Habilitar CORS para aceptar encabezados Authorization
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

        return $behaviors;
    }
    // Acción para iniciar sesión
    public function actionLogin()
    {
        // Se obtienen los datos enviados por el cliente
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        // Se verifica que se hayan enviado los datos
        if (!$username || !$password) {
            throw new UnauthorizedHttpException("Nombre de usuario y contraseña son obligatorios.");
        }
        // Se busca el usuario por su nombre de usuario
        $user = User::findByUsername($username);
        // Se verifica que el usuario exista y que la contraseña sea correcta
        if (!$user || !$user->validatePassword($password)) {
            throw new UnauthorizedHttpException("Credenciales incorrectas.");
        }

        return [
            "message" => "Inicio de sesión exitoso.",
            "username" => $user->username,
            "token" => $user->generateJwt(),
            "expira" => date('Y-m-d H:i:s', time() + (30 * 60)) // Formato de fecha y hora
        ];        
    }
}
