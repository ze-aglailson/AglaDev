<?php

require_once __DIR__.'/../vendor/autoload.php';

use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;
use \App\Http\Middleware\Queue as MiddlewareQueue;

//CARREGA AS VARIAVEIS DE AMBIENTE
Environment::load(__DIR__.'/../');

//DEFINE AS CONFIGURAÇÕES DE BANCO DE DADOS
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

//DEFINE A COSTANTE DE URL
define('URL', getenv('URL'));

//DEFINE O VALOR PADRÃO DAS VARIAVIES
View::init([
    'URL' => URL
]);

//DEFINE O MAPEAMENTO DE MIDDLEWARES
MiddlewareQueue::setMap([
    'maintenance'           => \App\Http\Middleware\Maintenance::class,
    'required-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
    'required-admin-login'  => \App\Http\Middleware\RequireAdminLogin::class,
    'api'                   => \App\Http\Middleware\Api::class,
    'user-basic-auth'       => \App\Http\Middleware\UserBasicAuth::class,
    'jwt-auth'              => \App\Http\Middleware\JWTAuth::class,
    'cache'                 => \App\Http\Middleware\Cache::class
]);

//DEFINE O MAPEAMENTO DE MIDDLEWARES PADRÕES EXECUTADO EM TODAS AS ROTAS
MiddlewareQueue::setDefault([
    'maintenance'
]);