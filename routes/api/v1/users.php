<?php
/*
    Middlewares de autenticação : basic_auth e JWT
    Middleware de autenticação atual:JWT
*/
use \App\Http\Response;
use \App\Controller\Api;

//ROTA DE LISTAGEM DE USUÁRIOS
$obRouter->get('/api/v1/users',[
    'middlewares' =>[
        'api',
        'jwt-auth',
        'cache'
    ],
    function($request){
        return new Response(200, Api\User::getUsers($request),'application/json');
    }
]);

//ROTA DE CONSULTA DO USUARIO ATUAL 'CONECTADO ' 'NÃO DEVE SER CACHEADA'
$obRouter->get('/api/v1/users/me',[
    'middlewares' =>[
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(200,Api\User::getCurrentUser($request),'application/json');
    }
]);
//ROTA DE CONSULTA INDIVIDUAL DE USUARIO
$obRouter->get('/api/v1/users/{id}',[
    'middlewares' =>[
        'api',
        'jwt-auth',
        'cache'
        
    ],
    function($request, $id){
        return new Response(200, Api\User::getUser($request, $id),'application/json');
    }
]);

//ROTA DE CADASTRO DE USUARIOS
$obRouter->post('/api/v1/users',[
    'middlewares' =>[
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(201, Api\User::setNewUser($request),'application/json');
    }
]);
//ROTA DE EDIÇÃO DE USUARIOS
$obRouter->put('/api/v1/users/{id}',[
    'middlewares' =>[
        'api',
        'jwt-auth'
    ],
    function($request,$id){
        return new Response(200, Api\User::setEditUser($request,$id),'application/json');
    }
]);

//ROTA DE EXCLUSÃO DE USUARIO
$obRouter->delete('/api/v1/users/{id}',[
    'middlewares' =>[
        'api',
        'jwt-auth'
    ],
    function($request,$id){
        return new Response(200, Api\User::setDeleteUser($request,$id),'application/json');
    }
]);