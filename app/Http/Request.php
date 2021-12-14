<?php

namespace App\Http;

class Request{

    /**
     * 'Instacia do Router'  Rota atual requisitada
     * @var Router
     */
    private $router;

    /**
     * Método da requisição
     * @var string
     */
    private $httpMethod;

    /**
     * URI da página
     * @var string
     */
    private $uri;

    /**
     * Parametros da URL ($_GET)
     * @var array
     */
    private $queryParams=[];

     /**
     * Variaveis recebidas no POST da página ($_POST)
     * @var array
     */
    private $postVars=[];

    /**
     * Cabeçalho da requisição  
     * @var array
     */
    private $headers = [];


    /**
     * Construtor da classe
     * @param $router instacia da rota atual
     */
    public function __construct($router)
    {
        $this->router       = $router;
        $this->queryParams  = $_GET ?? [];
        $this->headers      = getallheaders();
        $this->httpMethod   = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->setPostVars();

    }

    /**
     * Método responsável por definir as váriaveis do POST
    */
    private function setPostVars(){
      //VERIFICA O MÉTODO DA REQUISIÇÃO
      if($this->httpMethod == 'GET') return false;
      
      //POST PADRÃO
      $this->postVars = $_POST ?? [];

      //POST JSON 'VEM DA API'
      $inputRaw = file_get_contents('php://input');
      $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;

    }

    /**
     * Método responsável por definir a URI
     * 
     */
    private function setUri(){
      //URI COMPLETA (COM GETS)
      $this->uri = $_SERVER['REQUEST_URI'] ?? '';

      //REMOVE GETS DA URI
      $xURI = explode('?',$this->uri);

      $this->uri = $xURI[0];

    }


    /**
     * Método responsável por retornas a instancia de Router
     * @param $router instacia da rota atual
     */
    public function getRouter(){

      return $this->router;

    }
    
    /**
     * Método responsavel por retornar o metodo a Http da requisição
     * @return string
     */
    public function getHttpMethod(){
      return $this->httpMethod;
    }

    /**
     * Método responsavel por retornar a URI da requisição
     * @return string
     */
    public function getUri(){
      return $this->uri;
    }

    /**
     * Método responsavel por retornar o cabeçalho Http da requisição
     * @return array
     */
    public function getHeaders(){
      return $this->headers;
    }
    /**
     * Método responsavel por retornar os parametros da URL da requisição
     * @return array
     */
    public function getQueryParams(){
      return $this->queryParams;
    }
    /**
     * Método responsavel por retornar as variaveis POST da requisição
     * @return array
     */
    public function getPostVars(){
      return $this->postVars;
    }
}