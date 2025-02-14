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

Para trabajar con MongoDB en tu proyecto, primero necesitas asegurarte de que MongoDB esté instalado y corriendo correctamente. Si estás utilizando Laragon, te explicaré cómo configurar MongoDB en este entorno, así como cómo verificar la base de datos y las colecciones.

1. Descargar MongoDB
Ve al sitio oficial de MongoDB:

Dirígete a la página de descargas de MongoDB en: https://www.mongodb.com/try/download/community.
Selecciona la versión de Windows (o el sistema operativo que estés usando) y elige la versión "Current Release" de MongoDB.

**Recomendacion: Si usasa php8.2, utilice lo que es mongosh-2.3.9-win32-x64 y php_mongodb-1.15.2-8.2-nts-vs16-x64, por compatibilidad**

2. Descargar MongoDB:

Selecciona la opción de MSI (instalador para Windows) y descarga el archivo.
Instalar MongoDB:

3. Ejecuta el archivo MSI descargado y sigue el asistente de instalación.

-(Opcional) de seleccionar la opción de "Install MongoDB as a Service" (Instalar MongoDB como un servicio). Esto permitirá que MongoDB se ejecute automáticamente cuando inicies tu máquina.
-Deja las configuraciones predeterminadas para el proceso de instalación.

2. Descargar el Plugin de MongoDB para Laragon

Instalar MongoDB en Laragon:

Abre Laragon y dirígete a Menu -> Tools -> Quick Add -> MongoDB.
Laragon descargará e instalará MongoDB automáticamente como un servicio.
Verificar la instalación de MongoDB en Laragon:

Después de instalar MongoDB, puedes verificar que esté funcionando correctamente desde el panel de Laragon. Simplemente asegúrate de que los servicios Apache y MongoDB estén habilitados y en ejecución.

3. Descargar el DLL de MongoDB para Laragon

Si por alguna razón necesitas utilizar el DLL de MongoDB para conectarlo con PHP en Laragon, sigue estos pasos:

-Descargar el MongoDB PHP Driver:

Ve a la página de descargas del driver de MongoDB para PHP: MongoDB PHP Driver.
Descarga la versión correspondiente a tu versión de PHP y el sistema operativo que estés utilizando (por ejemplo, php_mongodb.dll para Windows y la versión correspondiente a PHP 8.2).

-Colocar el DLL en Laragon:
Copia el archivo .dll que descargaste y colócalo en la carpeta de extensiones de PHP en Laragon. Esta carpeta generalmente se encuentra en:

```bash
C:\laragon\bin\php\php-8.2.x-nts\ext\
```

Después, abre el archivo php.ini en el mismo directorio y agrega la siguiente línea al final del archivo para habilitar la extensión:

```bash
extension=mongodb.dll
```

Reiniciar Laragon:

Después de agregar la línea en el archivo php.ini, reinicia Laragon para que la configuración se aplique y la extensión de MongoDB se cargue correctamente.

4. Verificar MongoDB en la Consola o MongoDB Compass

Deberas abrir el MongoDB Compass 

Posterior ejecutar en el CMD
```bash
C:\Program Files\MongoDB\mongosh-2.3.9-win32-x64\bin mongosh.exe
```

De esta manera con el MongoDB Compass podras ingresar a tus BD locales

### **Paso 6: Ejecutar el Proyecto**

Una vez configurado todo, puedes acceder a la API en http://pruebatls.test:{puerto:80 u 8080}. La API está protegida por JWT, por lo que necesitarás obtener un token para acceder a los endpoints de libros y autores.


### **Documentacion de la API REST**

Endpoints Disponibles:
-Libros:
1. GET /v1/libro: Obtiene una lista de todos los libros.
2. GET /v1/libro/{id}: Obtiene los detalles de un libro específico.
3. POST /v1/libro: Crea un nuevo libro.
4. PUT /v1/libro/{id}: Actualiza un libro existente.
5. DELETE /v1/libro/{id}: Elimina un libro.

-Autores:
1. GET /v1/autor: Obtiene una lista de todos los autores.
2. GET /v1/autor/{id}: Obtiene los detalles de un autor específico.
3. POST /v1/autor: Crea un nuevo autor.
4. PUT /v1/autor/{id}: Actualiza un autor existente.
5. DELETE /v1/autor/{id}: Elimina un autor.

-Autenticación:
1. POST /v1/auth/login: Inicia sesión y obtiene un token JWT.


https://www.postman.com/lunar-crater-533910/pruebatls/collection/uvp4whw/pruebatls-api-biblioteca?action=share&creator=14423167&active-environment=14423167-79ba74b2-35a3-41c2-931d-b30d25f4663f