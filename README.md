# ImageManager-for-ABZ

This projects using on php 8.2 and laravel 12 versions.
Adding instrutions to READ.me
## How to run the service

Downloading the project
- Choose the local directory, where you would like to download and run this project.
- Run the following commands in your terminal

```sh
git clone https://github.com/alexkaasik/ImageManager-for-ABZ.git
cd ImageManager-for-ABZ
```

Copy the *.env.example* file to *.env*:
```sh
cp .env.example .env
```
After copying the file, we need to change
To generation app key, will automatically sets APP_KEY
```sh
php artisan key:generate
```
You need to find this name or add try adding this line to *.env*
- after "=" you should add tinypng api key.
```
API_KEY_FOR_TINIPNG=
```
Modify this value to match the domain or IP address you will use to accessing the server.
```
APP_URL=
```

Modify these values if you intend to use a database
- here options can be selected *mysql*, *mariadb*, *pgsql* (postgress sql) and *sqlsrv* (microsoft sql server).
```
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```
Installing all needed dependencies
```sh
composer install
```

We need to create a storage link
```sh
php artisan storage:link 
```

Set up the database migration system
```sh
php artisan migrate:install
```

Create fresh tables by running from all migrations files
```sh
php artisan migrate:fresh
```

Start the local development server
```sh
php artisan serve
```

## Author
- Aleksander Kaasik  [@alexkaasik](https://www.github.com/alexkaasik)
