## To run application run bellow command
docker-compose up -d 
### execute to php container then execute command lines below
##### run composer install
composer install
##### run command to public migration and config of laravel permission package
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
##### run command to migrate database
php artisan migrate
##### run seeder: sample data for role and permission
php artisan db:seed
