# Amlaki

Web application 

## Frameworks And Libraries 
- [Laravel is a web application framework](https://laravel.com).
- [JQuery](https://jquery.com).
- [Bootstrap 3](http://getbootstrap.com).
- [Bootstrap Notify](https://github.com/mouse0270/bootstrap-notify).
- [Bootstrap Validator](https://github.com/1000hz/bootstrap-validator).
- [Bootbox.js](http://bootboxjs.com).
- [smoke](https://github.com/alfredobarron/smoke).

# Project Prerequisites:
- [php v5.6.X](http://php.net).
- [apache](https://www.apache.org).
- [mysql v15.1](https://www.mysql.com).
- [composer](https://getcomposer.org).
- [nodejs and npm #LTS version#](https://nodejs.org).

### note: you can install php, apache, and mysql as one package, You can install [xampp](https://www.apachefriends.org/index.html) OR [wamp](http://www.wampserver.com) for windows.


## Installation:
- Clone the project on your machine.
- Run command 'composer install' from main directory of project.
```sh
$ cd project-dir
$ composer install
```
- Run command 'npm install' from main directory of project.
```sh
$ cd project-dir
$ npm install -dev
```
and for production
```sh
$ cd project-dir
$ npm install -producation
```
- Copy the .env.example file to .env in same directory (the file may be hidden).
```sh
$ cd project-dir
$ cp .env.example .env
```
- Modify the .env file with your data to make the local server work:
    - Set the database url.
    - Set the database name (the database should be exist).
    - Set the database username and password.
- Run command 'php artisan migrate' from main directory of project
    - Make sure the database is empty.
    - If you migrated the database before, Then use this command 'php artisan migrate:refresh'.
```sh
$ cd project-dir
$ php artisan migrate
```
- If you need dummy data, Run the command 'php artisan db:seed'. 
```sh
$ cd project-dir
$ php artisan db:seed
```
## Run the local server
- run the command 'php artisan serve'

```sh
$ cd project-dir
$ php artisan serve
```
by default the laravel server run with <http://127.0.0.1:8000>
you can modify the host and/or the port using --host and --port options

```sh
$ cd project-dir
$ php artisan serve --port=8085
```
