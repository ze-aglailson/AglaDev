<?php

//Controla as reques e reponse antes de ter acesso as controllers

namespace App\Http\Middleware;

class Queue{


    /**
     * Mapeamento de meddlewares
     * @var array
     * 
    */
    private static $map = [];

    /** 
     * Mapeamento de middlewares que serão carregadas em todas as rotas
     * @var array
    */
    private static $default = [];

    /**
     * Fila de middleware a serem executados
     * @var array
     * 
    */
    protected $middlewares = [];

    /**
     * Função de execução do controller 'closure da rota'
     * @var Closure
     * 
    */
    protected $controller;

    /**
     * Argumentos da função do controlador
     * @var array
     * 
    */
    protected $controllerArgs = [];


    /** 
    * Método responsavel pro construir a classe de fila de middleware
    * @param  array $middlewares
    * @param  Closure $controller
    * @param  array $controllerArgs
    *
    **/
    public function __construct($middlewares, $controller, $controllerArgs){
        $this->middlewares = array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * método responsavel pode definir o mapeameno de middlewares
     * @param array $map
    */
    public static function setMap($map){
        self::$map = $map;
    }

    /**
     * método responsavel pode definir o mapeameno de middleware padrões
     * @param array $default
    */
    public static function setDefault($default){
        self::$default = $default;
    }

    /**
     * Método responsavel por executar  o proximo nivel na fila de middlewares
     * @param Request $request
     * @return Response  
     **/ 
    public function next($request){

       //VERIFICA SE A FILA DE MIDDLEWARES ESTÁ VAZIA
       if(empty($this->middlewares)) return call_user_func_array($this->controller, $this->controllerArgs);

       //MIDLLEWARE
       $middleware = array_shift($this->middlewares);

       //VERIFICA O MAPEAMENTO
       if(!isset(self::$map[$middleware])){
           throw new \Exception("Problemas ao processar o middleware da requisição", 500);
           
       }
       
       //NEXT 'proxima camada de middleware'
       $queue = $this;
       $next = function($request) use($queue){
           return $queue->next($request);
       };

       //EXECUTA O MIDDLEWARE
       return ( new self::$map[$middleware])->handle($request, $next);
       
    }

}