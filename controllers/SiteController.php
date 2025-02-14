<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    // Acción para mostrar la página de inicio
    public function actionError()
    {
        // Si la petición es AJAX o JSON
        if (Yii::$app->request->isAjax || Yii::$app->request->acceptsJson()) {
            // Se establece el formato de respuesta a JSON
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'error' => 'Ocurrió un error en la aplicación.',
                'message' => Yii::$app->errorHandler->exception->getMessage(),
            ];
        }

        return $this->render('error');
    }
}
