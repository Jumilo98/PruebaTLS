<?php

namespace app\modules\v1\controllers;

use app\components\AuthComponent;
use app\models\Libro;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use MongoDB\BSON\ObjectId;
use Exception;
use Yii;

class LibroController extends ActiveController
{
    // Nombre de la clase del modelo
    public $modelClass = 'app\models\Libro';
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
        // Habilitar CORS para aceptar encabezados Authorization
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

    // GET: Listar todos los libros
    public function actionIndex()
    {
        // Configurar la respuesta como JSON
        Yii::$app->response->format = Response::FORMAT_JSON;
        // Obtener todos los libros
        $libros = Libro::find()->asArray()->all();
        // Formatear la respuesta
        return $this->formatLibrosResponse($libros, 'Lista de libros');
    }
    // GET: Obtener un libro por ID
    public function actionView($id)
    {
        try {
            // Validar el ID del libro
            if (!$this->isValidObjectId($id)) {
                throw new BadRequestHttpException("ID Libro inválido.");
            }

            $objectId = new ObjectId($id);
            // Buscar el libro por ID
            $libro = Libro::findOne(['_id' => $objectId]);
            // Si el libro no existe, lanza una excepción
            if (!$libro) {
                throw new NotFoundHttpException("Libro no encontrado.");
            }
            // Formatear la respuesta
            return $this->formatLibroResponse($libro, 'Libro encontrado.');
        } catch (Exception $e) {
            // Lanzar una excepción si el ID no es válido
            throw new NotFoundHttpException($e->getMessage());
        }
    }
    // POST: Crear uno o varios libros
    public function actionCreate()
    {
        // Configurar la respuesta como JSON
        Yii::$app->response->format = Response::FORMAT_JSON;
        // Obtener los datos de la solicitud
        $request = Yii::$app->request->bodyParams;
        // Lanzar una excepción si el cuerpo de la solicitud está vacío
        if (empty($request)) {
            throw new BadRequestHttpException("El cuerpo de la solicitud está vacío.");
        }
        // Si se recibe un array, procesar como múltiples libros
        if (is_array($request) && isset($request[0])) {
            return $this->crearMultiplesLibros($request);
        }
        // Si se recibe un solo objeto, procesar como un único libro
        return $this->crearUnLibro($request);
    }
    // Función auxiliar para crear un solo libro
    private function crearUnLibro($data)
    {
        $libro = new Libro();
        // Si se proporciona un _id válido, úsalo, de lo contrario MongoDB generará uno
        if (!empty($data['_id']) && preg_match('/^[0-9a-fA-F]{24}$/', $data['_id'])) {
            $libro->_id = new ObjectId($data['_id']);
        }

        $libro->attributes = $this->convertirIdsObjectId($data);

        if ($libro->save()) {
            return ["message" => "Libro creado con éxito.", "libro" => $libro];
        }
        return ['error' => 'No se pudo guardar el libro.', 'detalles' => $libro->getErrors()];
    }
    // Función auxiliar para crear múltiples libros
    private function crearMultiplesLibros($librosData)
    {
        // Inicializar arrays para guardar libros y errores
        $librosGuardados = [];
        $errores = [];
        // Recorrer los datos de los libros
        foreach ($librosData as $data) {
            $libro = new Libro();
            // Si se proporciona un _id válido, úsalo, de lo contrario MongoDB generará uno
            if (!empty($data['_id']) && preg_match('/^[0-9a-fA-F]{24}$/', $data['_id'])) {
                $libro->_id = new ObjectId($data['_id']);
            }

            $libro->attributes = $this->convertirIdsObjectId($data);

            if ($libro->save()) {
                $librosGuardados[] = $libro;
            } else {
                $errores[] = ['libro' => $data, 'error' => $libro->getErrors()];
            }
        }
        // Si hay errores, devolver la lista de libros guardados y errores
        if (!empty($errores)) {
            return ['message' => 'Algunos libros no pudieron guardarse.', 'libros_guardados' => $librosGuardados, 'errores' => $errores];
        }
        // Devolver la lista de libros guardados
        return ['message' => 'Todos los libros fueron creados con éxito.', 'libros' => $librosGuardados];
    }
    // PUT: Actualizar un libro existente
    public function actionUpdate($id)
    {
        try {
            // Validar el ID del libro
            if (!$this->isValidObjectId($id)) {
                throw new BadRequestHttpException("ID Libro inválido.");
            }
            // Buscar el libro por ID
            $libro = Libro::findOne(['_id' => new ObjectId($id)]);
            if (!$libro) {
                throw new NotFoundHttpException("Libro no encontrado.");
            }
            // Obtener los datos de la solicitud
            $request = Yii::$app->request->post();
            $libro->attributes = $this->convertirIdsObjectId($request);
            
            if ($libro->save()) {
                return ["message" => "Libro actualizado con éxito.", "libro" => $libro];
            }
            return ['error' => 'No se pudo actualizar el libro.'];
        } catch (Exception $e) {
            throw new NotFoundHttpException("ID inválido.");
        }
    }
    // DELETE: Eliminar un libro
    public function actionDelete($id)
    {
        try {
            // Validar el ID del libro
            if (!$this->isValidObjectId($id)) {
                throw new BadRequestHttpException("ID Libro inválido.");
            }
            // Buscar el libro por ID
            $libro = Libro::findOne(['_id' => new ObjectId($id)]);
            // Si no se encuentra el libro, lanzar una excepción
            if (!$libro) {
                throw new NotFoundHttpException("Libro no encontrado.");
            }

            if ($libro->delete()) {
                return ["message" => "Libro eliminado con éxito."];
            }

            return ['error' => 'No se pudo eliminar el libro.'];
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
    // Función auxiliar: Convertir IDs de autores en ObjectId
    private function convertirIdsObjectId($data)
    {
        if (!empty($data['autor_ids']) && is_array($data['autor_ids'])) {
            $data['autor_ids'] = array_map(fn($id) => new ObjectId($id), $data['autor_ids']);
        }
        return $data;
    }
    // Función auxiliar: Estructurar respuesta de un libro
    private function formatLibroResponse($libro, $message): array
    {
        // Formatear la respuesta
        $libro = [
            '_id' => (string) $libro['_id'],
            'titulo' => $libro['titulo'],
            'autor_ids' => array_map(fn($id) => (string) $id, $libro['autor_ids'] ?? []),
            'anio_publicacion' => $libro['anio_publicacion'],
            'descripcion' => $libro['descripcion'],
        ];

        return [
            'message' => $message,
            'libro' => $libro
        ];
    }
    // Función auxiliar: Estructurar respuesta de múltiples libros
    private function formatLibrosResponse($libros, $message): array
    {
        // Formatear la respuesta
        $libros =[
            'libro' => array_map(fn($libro) => [
                '_id' => (string) $libro['_id'],
                'titulo' => $libro['titulo'],
                'autor_ids' => array_map(fn($id) => (string) $id, $libro['autor_ids'] ?? []),
                'anio_publicacion' => $libro['anio_publicacion'],
                'descripcion' => $libro['descripcion'],
            ], $libros)
        ];

        return [
            'message' => $message,
            'libros' => $libros
        ];
    }
}
