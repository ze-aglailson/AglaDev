<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router{

    /**
    * URL completa do projeto (raiz)
    *@var string
    */
    private $url = '';

    /**
    * Prefixo de todas as rotas
    *@var string
    */
    private $prefix = '';

    /**
    * Indice de rotas
    *@var array
    */
    private $routes = [];

    /**
    * Instacia de Request
    *@var Request
    */
    private $request;

    /**
    * content type do response
    *@var string
    */
    private $contentType = 'text/html';

    /**
    * Método responsavel por iniciar a classe
    * @param  string  $url
    */
    public function __construct($url){
        $this->request  =  new Request($this);
        $this->url      =  $url;
        $this->setPrefix();
    }

    /**
     * Método responsavel por alterar o valor do contentType
     * @param string $contentType
     * 
    */
    public function setContentType($contentType){
        $this->contentType = $contentType;
    }


    /**
    * Método responsavel por definir o prefixo das rotas
    */
    public function setPrefix(){
        //INFROMAÇÕES DA URL ATUAL
        $parseUrl = parse_url($this->url);

        //DEFINE O PREFIXO
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
    * Método responsavel por adicionar uma rota na classe
    * @param string $method
    * @param string $route
    * @param array  $params
    */
    public function addRoute($method, $route, $params = []){
        //VALIDAÇÃO DOS PARAMETROS
        foreach ($params as $key => $value) {
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        // MIDDLEWARES DA ROTA
        $params['middlewares'] = $params['middlewares'] ?? [];

        //VARIAVEIS DA ROTA
        $params['variables'] = [];

        //PADRÃO DE VALIDAÇÃO DAS VARIAVEIS da rota
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){

            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];

        }

        //REMOVE BARRA NO FINAL DA ROTA
        $route = rtrim($route, '/');

        //PADRÃO DE VALIDAÇÃO DA  URL
        $patternRoute = '/^'.str_replace('/','\/', $route).'$/';

        //ADICIONA A ROTA DENTRO DA CLASSE
        $this->routes[$patternRoute][$method] = $params;

    }


    /**
    * Método responsável por definir uma rota GET
    * @param    string  $route
    * @param    array   $ params
    */
    public function get($route, $params = []){
        return $this->addRoute('GET', $route, $params);
    }

    /**
    * Método responsável por definir uma rota POST
    * @param    string  $route
    * @param    array   $ params
    */
    public function post($route, $params = []){
        return $this->addRoute('POST', $route, $params);
    }

    /**
    * Método responsável por definir uma rota PUT
    * @param    string  $route
    * @param    array   $ params
    */
    public function put($route, $params = []){
        return $this->addRoute('PUT', $route, $params);
    }

    /**
    * Método responsável por definir uma rota DELETE
    * @param    string  $route
    * @param    array   $ params
    */
    public function delete($route, $params = []){
        return $this->addRoute('DELETE', $route, $params);
    }

     /**
     * Método responsavel por retornar a URI desconsiderando o prefixo
     * @return string
     */
    public function getUri(){
        //URI DA REQUEST
        $uri = $this->request->getUri();
        
        //FATIA A URI COM O PREFIXO
        $Xuri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        //RETORNA A URI SEM PREFIXO
        return rtrim(end($Xuri),'/');
    }

    /**
     * Método responsavel por retornar os dados da rota atual
     * @return array
     */
    private function getRoute(){
        //URI
        $uri = $this->getUri();

        //METHOD
        $httpMethod = $this->request->getHttpMethod();

        //VALIDA AS ROTAS
        foreach ($this->routes as $patternRoute => $methods) {

            //VERIFICA SE A URI BATE O PADRÃO
            if(preg_match($patternRoute, $uri, $matches)){

                //VERIFICA O MÉTODO
                if(isset($methods[$httpMethod])){

                    //REMOVE A PRIMEIRA POSIÇÃO 'NÃO SERÁ USADA'
                    unset($matches[0]);


                    //VARIAVEIS PROCESSADAS
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    //RETORNO DOS PARAMETROS DA ROTA
                    return $methods[$httpMethod];
                }
                //MÉTODO NÃO PERMITIDO/DEFINIDO
                throw new Exception("Método não permitido", 405);
                
            }
        }
        //URL NÃO ENCONTRADA
        throw new Exception("URL não encontrada", 404);

    }

    /**
    * Método responsavel por executar a rota atual
    * @return Response
    */
    public function run(){
        try{
            
            //OBTEM A ROTA ATUAL
            $route = $this->getRoute();

           //VERIFICA O CONTROLADOR
           if(!isset($route['controller'])){
               throw new Exception("A URL não pôde ser processada", 500);
           }

           //ARGUMENTOS DA FUNÇÃO
           $args = [];

           //REFLECTION
           $reflection = new ReflectionFunction($route['controller']);

           foreach ($reflection->getParameters() as $parameter) {

            $name = $parameter->getName();
            $args[$name] = $route['variables'][$name] ?? '';

           }

           //RETORNA A EXECUÇÃO DA FILA DE MIDDLEWARES
           return(new MiddlewareQueue($route['middlewares'], $route['controller'], $args))->next($this->request);

        }catch(Exception $e){
            return new Response($e->getCode(), $this->getErrorMessage($e->getMessage()), $this->contentType);
        }
    }

    /**
     * Método responsável por retornar a mensagme de erro de acordo com o contentype
     * @param string $message
     * @return mixed
     */
    public function getErrorMessage($message){
        switch ($this->contentType) {
            case 'application/json':
                return [
                    'error'=>$message
                ];
            break;
            default:
                return $message;
            break;
            
        }
    }

    /**
    * Método responsavel por retornar a url atual
    * @return string
    */
    public function getCurrentUrl(){
        return $this->url.$this->getUri();
    }


    /**
     * Método responsavel por redirecionar a URL
     * @param string $route
    */
    public function redirect($route){

        //URL
        $url = $this->url.$route;
        
        //EXECUTA O REDIRECT
        header('location: '.$url);
        exit;

    }

}