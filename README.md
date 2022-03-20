## Installation
***
To use my app, you need :

- To clone this repo
- Run a terminal
- Go to the folder
- Then run the following command
```text
composer install

npm install

npm install sass-loader@^12.0.0 sass --save-dev
```
- Open the file name **.env**
- Open laragon, mamp or similar
- Change the following line to create a database ([How to create a database with symfony](https://symfony.com/doc/current/configuration.html#configuring-environment-variables-in-env-files))
```dotenv
 DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
```
- Run the following command
```text
php bin/console doctrine:database:create

php bin/console m:mi

php bin/console d:m:m

php bin/console d:f:l

npm run watch
```
- Open another terminal
- Run
```text 
symfony server:start
```

## How to use
***
There is the admin login and the user login if you want to try it out.

``email : test1@gmail.com | password : password``

``email : admin@gmail.com | password : admin``

## Contact
***
Got any problem ? Contact me

Discord : Bleache#7203