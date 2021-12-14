<?php

namespace  App\Http;

class Response{

    /**
    * Código do Status HTTP
    *@var integer
    */
    private $httpCode = 200;

    /**
    * Cabecçalho do response
    *@var array
    */
    private $headers = [];

    /**
    * Tipo de conteudo que está sendo retornado 'html\json'
    *@var string
    */
    private $contentType = 'text/html';

    /**
    * Conteudo do response
    *@var mixed
    */
    private $content;


    /**
    * Método responsavel por iniciar a classe e definir os valores
    * @method  __construct
    * @param    integer $httpCode
    * @param    mixed  $content 
    * @param    string  $contentType
    */
    public function __construct($httpCode,$content,$contentType = 'text/html'){
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }


    /**
    * Método resoponsavel por alterar o contentType do response
    * @method  setContentType
    * @param   string $contentType
    */
    public function setContentType($contentType){
        $this->contentType = $contentType;
        $this->addHeader('Content-Type',$contentType);
    }

    /**
    * Método responsavel por adicionar um registro no cabeçalho do response
    * @method  addHeader
    * @param   string $key
    * @param   string $value
    */
    public function addHeader($key, $value){
        $this->headers[$key] = $value;
    
    }


    /**
    * Método responsavel por enviar os headers para o navegador
    */
    public function sendHeaders(){
        //STATUS
        http_response_code($this->httpCode);

        //SETA OS HEADERS
        foreach ($this->headers as $key => $value) {
            header($key.': '.$value);
        }
    }

    /**
    * Método responsavel por enviar a resposta para o usuário 
    */
    public function sendResponse(){
        
        //ENVIA OS HEADERS
        $this->sendHeaders();

        //IMPRIME O CONTEUDO
        switch ($this->contentType){
            case 'text/html';
                echo $this->content;
            exit;
            case 'application/json';
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }
    }


}