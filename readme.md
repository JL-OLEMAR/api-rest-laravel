# API REST con LARAVEL  

## Ajustes
-----------------en---cmd----------------------------------------                                             
Primero tiene que actualizar el composer en una ventana de cmd:
composer update

----------------en---la-consola-de-artisan-----------------------                                             
Recorda que deves tener el archivo .env
Si no lo tienes tienes que copiar desde el archivo .env.example y completar la BD.

Puede generar la clave de cifrado de la aplicación con este comando:
php clave artesanal: generar

Luego, cree un archivo de caché para una carga de configuración más rápida usando este comando:
php artisan config: caché

O, sirva la aplicación en el servidor de desarrollo PHP usando este comando:

servicio artesanal php



--------CONFIGURAR PARA LA SUBIDA DE IMAGENES------------------

en la carpeta storage/app/ crear las carpetas
/images
/users

luego en la carpeta: config/filesystems.php pegar lo sgte:

    'users' => [
        'driver' => 'local',
        'root' => storage_path('app/users'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],

    'images' => [
        'driver' => 'local',
        'root' => storage_path('app/images'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
