<p align="center">
    <h1 align="center">PRUEBATLS JIMMY GRANIZO </h1>
    <br>
</p>

# **Biblioteca API REST - Proyecto Yii2 con MongoDB**

Este proyecto es una API REST para gestionar una biblioteca virtual de libros y autores utilizando el framework Yii2 y la base de datos MongoDB. La API permite administrar libros, autores, y sus relaciones, y está protegida por autenticación mediante JWT.

## **Estructura del Proyecto**

La estructura del proyecto es la siguiente:

DIRECTORY STRUCTURE
-------------------

      PRUEBATLS/ │ 
      ├── assets/ # Archivos estáticos (CSS, JS, etc.) 
      ├── commands/ # Comandos personalizados de Yii2 
      ├── config/ # Archivos de configuración 
      ├── controllers/ # Controladores de la API 
      ├── mail/ # Plantillas de correo (si las hay) 
      ├── models/ # Modelos de la base de datos (MongoDB) 
      ├── modules/ # Módulos de la aplicación 
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


REQUIREMENTS
------------

## **Requisitos del Proyecto**

Asegúrate de cumplir con los siguientes requisitos antes de comenzar:

- **PHP 8.2 o superior**.
- **MongoDB** (con el driver de MongoDB para PHP).
- **Composer** (para gestionar dependencias).
- **Laravel/Apache/Nginx** como servidor web (en este caso, se usa Laragon).

## **Instalación del Proyecto**

### **Paso 1: Clonar el Repositorio**

Primero, clona el repositorio en tu máquina local. Abre una terminal y ejecuta el siguiente comando:

```bash
git clone https://github.com/Jumilo98/PruebaTLS.git
cd proyecto


INSTALLATION
------------

### Install via Composer


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.


TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](https://codeception.com/).
By default, there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 


### Running  acceptance tests

To execute acceptance tests do the following:  

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full-featured
   version of Codeception

3. Update dependencies with Composer 

    ```
    composer update  
    ```

4. Download [Selenium Server](https://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ```

    In case of using Selenium Server 3.0 with Firefox browser since v48 or Google Chrome since v53 you must download [GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and launch Selenium with it:

    ```
    # for Firefox
    java -jar -Dwebdriver.gecko.driver=~/geckodriver ~/selenium-server-standalone-3.xx.x.jar
    
    # for Google Chrome
    java -jar -Dwebdriver.chrome.driver=~/chromedriver ~/selenium-server-standalone-3.xx.x.jar
    ``` 
    
    As an alternative way you can use already configured Docker container with older versions of Selenium and Firefox:
    
    ```
    docker run --net=host selenium/standalone-firefox:2.53.0
    ```

5. (Optional) Create `yii2basic_test` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.


6. Start web server:

    ```
    tests/bin/yii serve
    ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```

### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run --coverage --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit --coverage --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit --coverage --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
