<?php

use \App\Http\Response;
use \App\Controller\Pages;


//ROTA HOME
$obRouter->get('/', [
    'middlewares'=>[
        'cache'
    ],
    function(){
        return new Response(200,Pages\Home::getHome());
    }
]);

//ROTA SOBRE
$obRouter->get('/orcamento', [
    'middlewares'=>[
        'cache'
    ],
    function(){
        return new Response(200,Pages\Orcamento::getOrcamento());
    }
]);

//ROTA DEPOIMENTOS
$obRouter->get('/depoimentos', [
    'middlewares'=>[
        'cache'
    ],
    function($request){
        return new Response(200,Pages\Testimony::getTestimonies($request));
    }
]);

//ROTA DEPOIMENTOS POST (INSERT)
$obRouter->post('/depoimentos', [
    function($request){
        return new Response(200,Pages\Testimony::insertTestimony($request));
    }
]);

/* 
//ROTA DINAMICA
$obRouter->get('/pagina/{idPagina}/{acao}', [
    function($idPagina, $acao){
        return new Response(200,'Página '.$idPagina.' - '.$acao);
    }
]); */