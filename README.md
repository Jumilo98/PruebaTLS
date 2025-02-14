# **Biblioteca API REST - Proyecto Yii2 con MongoDB y JWT (PRUEBATLS Jimmy Granizo)**

Este proyecto es una API REST para gestionar una biblioteca virtual de libros y autores utilizando el framework Yii2 y la base de datos MongoDB. La API permite administrar libros, autores, y sus relaciones, y está protegida por autenticación mediante JWT.

## **Estructura del Proyecto**

La estructura del proyecto es la siguiente:

      PRUEBATLS/ │ 
      ├── assets/ # Archivos estáticos (CSS, JS, etc.) 
      ├── commands/ # Comandos personalizados de Yii2 
      ├── components/ # Componente reutilizable para Autenticar por JWT
      ├── config/ # Archivos de configuración 
      ├── controllers/ # Controladores de la API
      ├── mail/ # Plantillas de correo (si las hay) 
      ├── models/ # Modelos de la base de datos (MongoDB) 
      ├── modules/ # Módulos de la aplicación 
        ├── v1/ # Version de Modulo para la API 
            ├── controllers/ # Controladores de la API 
      ├── runtime/ # Archivos generados en tiempo de ejecución 
      ├── tests/ # Archivos de pruebas 
      ├── vendor/ # Dependencias instaladas por Composer 
      ├── views/ # Vistas (en caso de que se necesiten) 
      ├── web/ # Archivos públicos y de configuración web (index.php, etc.) 
      ├── components/ # Componentes personalizados de Yii2 
      ├── config/db.php # Configuración de conexión a MongoDB 
      ├── composer.json # Archivo de configuración de Composer 
      ├── docker-compose.yml # Configuración de Docker (si aplica) 
      ├── LICENSE.md # Licencia del proyecto 
      ├── README.md # Este archivo 
      ├── requirements.php # Requerimientos de PHP para el proyecto 
      ├── yii # Comando Yii2 
      └── .gitignore # Archivos ignorados por Git

## **Requisitos del Proyecto**

Asegúrate de cumplir con los siguientes requisitos antes de comenzar:

- **PHP 8.2 o superior**.
- **MongoDB** (con el driver de MongoDB para PHP).
- **Composer** (para gestionar dependencias).
- **Apache/Nginx** como servidor web (en este caso, se usa Laragon).

## **Instalación del Proyecto**

### **Paso 1: Clonar el Repositorio**

Primero, clona el repositorio en tu máquina local. Abre una terminal y ejecuta el siguiente comando:

```bash
git clone https://github.com/Jumilo98/PruebaTLS.git
cd PruebaTLS
```
### **Paso 2: Instalar las Dependencias**

Instala las dependencias del proyecto usando Composer. Ejecuta el siguiente comando en la raíz del proyecto:

```bash
composer install
```

Esto descargará todas las dependencias necesarias definidas en el archivo composer.json.

### **Paso 3: Configuración de MongoDB**

Si es necesario, configura la conexión a MongoDB en el archivo config/db.php. Si utilizas MongoDB en Laragon, puede que debas actualizar la URI de conexión (por ejemplo, si la contraseña o el usuario es diferente):

```php
return [
    'class' => 'yii\mongodb\Connection',
    'dsn' => 'mongodb://localhost:27017/Biblioteca',
    //'username' => 'root',
    //'password' => '',
    //'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
```
### **Paso 4: Iniciar el Proyecto con Laragon**

Si estás utilizando Laragon para el servidor, simplemente sigue estos pasos:

1. Abre Laragon.
2. Inicia los servicios Apache y MongoDB desde el panel de Laragon.
3. Coloca el proyecto clonado en el directorio www de Laragon.
4. Accede al proyecto desde tu navegador en: http://pruebatls.test:{puerto:80 u 8080}.

### **Paso 5: Verificar MongoDB**

Asegúrate de que MongoDB está ejecutándose correctamente. Puedes verificar esto abriendo MongoDB Compass o conectándote a MongoDB mediante la terminal:

```bash
mongo
use biblioteca  # Cambia al nombre de tu base de datos
show collections  # Verifica que las colecciones estén creadas
```

### **Paso 5: Verificar MongoDB**

Una vez configurado todo, puedes acceder a la API en http://pruebatls.test:{puerto:80 u 8080}. La API está protegida por JWT, por lo que necesitarás obtener un token para acceder a los endpoints de libros y autores.


### **Documentacion de la API REST**

Endpoints Disponibles
-Libros:
    GET /v1/libro: Obtiene una lista de todos los libros.
    GET /v1/libro/{id}: Obtiene los detalles de un libro específico.
    POST /v1/libro: Crea un nuevo libro.
    PUT /v1/libro/{id}: Actualiza un libro existente.
    DELETE /v1/libro/{id}: Elimina un libro.

-Autores:
    GET /v1/autor: Obtiene una lista de todos los autores.
    GET /v1/autor/{id}: Obtiene los detalles de un autor específico.
    POST /v1/autor: Crea un nuevo autor.
    PUT /v1/autor/{id}: Actualiza un autor existente.
    DELETE /v1/autor/{id}: Elimina un autor.

-Autenticación:
    POST /v1/auth/login: Inicia sesión y obtiene un token JWT.


https://www.postman.com/lunar-crater-533910/pruebatls/collection/uvp4whw/pruebatls-api-biblioteca?action=share&creator=14423167&active-environment=14423167-79ba74b2-35a3-41c2-931d-b30d25f4663f