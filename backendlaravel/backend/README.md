//buenas


//para usarlo use

composer install

//cree ne la base de datos una base de datos llamada rappiuts, vaya al .env y cambia la contra si lo cambia

DB_CONNECTION=mysql
 DB_HOST=127.0.0.1
 DB_PORT=3306
DB_DATABASE=rappiuts
 DB_USERNAME=root
DB_PASSWORD=123


//luego en la carpeta

php artisan migrate


//y este comando

php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"