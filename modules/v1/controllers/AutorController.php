<?php

namespace app\modules\v1\controllers;

use app\components\AuthComponent;
use app\models\Autor;
use app\models\User;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use MongoDB\BSON\ObjectId;
use Exception;
use Yii;

class AutorController extends ActiveController
{
    // Nombre de la clase del modelo
    public $modelClass = 'app\models\Autor';
    // Configuración de los comportamientos del controlador
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
        // Habilitar CORS 
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

        return $behaviors;
    }
    // Autenticar al usuario antes de ejecutar cualquier acción
    public function beforeAction($action)
    {
        // Autenticar al usuario
        AuthComponent::authenticate();
        return parent::beforeAction($action);
    }

    public function actions()
    {
        $actions = parent::actions();
        // Deshabilitar acciones innecesarias
        unset($actions['index'], $actions['view'], $actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }
    // GET: Listar todos los autores
    public function actionIndex()
    {
        // Configurar la respuesta como JSON
        Yii::$app->response->format = Response::FORMAT_JSON;
        // Obtener todos los autores
        $autores = Autor::find()->asArray()->all();
        // Formatear la respuesta
        return $this->formatAutoresResponse($autores, 'Lista de autores');
    }
    // GET: Obtener un autor por ID
    public function actionView($id)
    {
        try {
            // Validar el ID del autor
            if (!$this->isValidObjectId($id)) {
                throw new BadRequestHttpException("ID Autor inválido.");
            }

            $objectId = new ObjectId($id);
            // Buscar el autor por ID
            $autor = Autor::findOne(['_id' => $objectId]);
            // Si no se encuentra el autor, lanzar una excepción
            if (!$autor) {
                throw new NotFoundHttpException("Autor no encontrado.");
            }
            // Formatear la respuesta
            return $this->formatAutorResponse($autor, 'Autor encontrado.');
        } catch (Exception $e) {
            // Lanzar una excepción si el ID no es válido
            throw new NotFoundHttpException($e->getMessage());
        }
    }
    // POST: Crear uno o varios autores
    public function actionCreate()
    {
        // Configurar la respuesta como JSON
        Yii::$app->response->format = Response::FORMAT_JSON;
        // Obtener los datos de la solicitud
        $request = Yii::$app->request->bodyParams;
        // Lanzar una excepción si la solicitud está vacía
        if (empty($request)) {
            throw new BadRequestHttpException("El cuerpo de la solicitud está vacío.");
        }
        // Si se recibe un array, procesar como múltiples autores
        if (is_array($request) && isset($request[0])) {
            return $this->crearMultiplesAutores($request);
        }
        // Si se recibe un solo objeto, procesar como un único autor
        return $this->crearUnAutor($request);
    }
    // Función auxiliar para crear un solo autor
    private function crearUnAutor($data)
    {
        $autor = new Autor();
        // Si se proporciona un _id válido, úsalo, de lo contrario MongoDB generará uno
        if (!empty($data['_id']) && preg_match('/^[0-9a-fA-F]{24}$/', $data['_id'])) {
            $autor->_id = new ObjectId($data['_id']);
        }

        $autor->attributes = $data;
        
        if ($autor->save()) {
            return ["message" => "Autor creado con éxito.", "autor" => $autor];
        }

        return ['error' => 'No se pudo guardar el autor.', 'detalles' => $autor->getErrors()];
    }
    // Función auxiliar para crear múltiples autores
    private function crearMultiplesAutores($autoresData)
    {
        // Inicializar arrays para autores guardados y errores
        $autoresGuardados = [];
        $errores = [];
        // Iterar sobre los datos de los autores
        foreach ($autoresData as $data) {
            $autor = new Autor();
            // Si se proporciona un _id válido, úsalo, de lo contrario MongoDB generará uno
            if (!empty($data['_id']) && preg_match('/^[0-9a-fA-F]{24}$/', $data['_id'])) {
                $autor->_id = new ObjectId($data['_id']);
            }

            $autor->attributes = $data;

            if ($autor->save()) {
                $autoresGuardados[] = $autor;
            } else {
                $errores[] = ['autor' => $data, 'error' => $autor->getErrors()];
            }
        }
        // Si hay errores, devolver la lista de autores guardados y errores
        if (!empty($errores)) {
            return ['message' => 'Algunos autores no pudieron guardarse.', 'autores_guardados' => $autoresGuardados, 'errores' => $errores];
        }
        // Devolver la lista de autores guardados
        return ['message' => 'Todos los autores fueron creados con éxito.', 'autores' => $autoresGuardados];
    }
    // PUT: Actualizar un autor existente
    public function actionUpdate($id)
    {
        try {
            // Validar el ID del autor
            if (!$this->isValidObjectId($id)) {
                throw new BadRequestHttpException("ID Autor inválido.");
            }
            // Buscar el autor por ID
            $autor = Autor::findOne(['_id' => new ObjectId($id)]);
            // Si no se encuentra el autor, lanzar una excepción
            if (!$autor) {
                throw new NotFoundHttpException("Autor no encontrado.");
            }
            // Obtener los datos de la solicitud
            $request = Yii::$app->request->post();
            $autor->attributes = $request;

            if ($autor->save()) {
                return ["message" => "Autor actualizado con éxito.", "autor" => $autor];
            }
            return ['error' => 'No se pudo actualizar el autor.'];
        } catch (Exception $e) {
            // Lanzar una excepción si el ID no es válido
            throw new NotFoundHttpException("ID inválido.");
        }
    }
    // DELETE: Eliminar un autor
    public function actionDelete($id)
    {
        try {
            // Validar el ID del autor
            if (!$this->isValidObjectId($id)) {
                throw new BadRequestHttpException("ID Autor inválido.");
            }
            // Buscar el autor por ID
            $autor = Autor::findOne(['_id' => new ObjectId($id)]);
            // Si no se encuentra el autor, lanzar una excepción
            if (!$autor) {
                throw new NotFoundHttpException("Autor no encontrado.");
            }

            if ($autor->delete()) {
                return ["message" => "Autor eliminado con éxito."];
            }

            return ['error' => 'No se pudo eliminar el autor.'];
        } catch (Exception $e) {
            // Lanzar una excepción si el ID no es válido
            throw new NotFoundHttpException("ID inválido.");
        }
    }
    // Función auxiliar: Validar ObjectId de MongoDB
    private function isValidObjectId($id): bool
    {
        // Validar el formato del ID
        return preg_match('/^[0-9a-fA-F]{24}$/', $id);
    }
    // Función auxiliar: Estructurar respuesta de un autor
    private function formatAutorResponse($autor, $message): array
    {
        // Formatear la respuesta
        $autor = [
            '_id' => (string) $autor->_id,
            'nombre_completo' => $autor->nombre_completo,
            'fecha_nacimiento' => $autor->fecha_nacimiento,
            'libros_escritos' => array_map(fn($libroId) => (string) $libroId, $autor->libros_escritos ?? [])
        ];

        return [
            'message' => $message,
            'autor' => $autor
        ];
    }
    // Función auxiliar: Estructurar respuesta de múltiples autores
    private function formatAutoresResponse($autores, $message): array
    {
        // Formatear la respuesta
        $autores =[
            'autor' => array_map(fn($autor) => [
                '_id' => (string) $autor['_id'],
                'nombre_completo' => $autor['nombre_completo'],
                'fecha_nacimiento' => $autor['fecha_nacimiento'],
                'libros_escritos' => array_map(fn($libroId) => (string) $libroId, $autor['libros_escritos'] ?? [])
            ], $autores)
        ];
        
        return [
            'message' => $message,
            'autores' => $autores
        ];
    }
}
