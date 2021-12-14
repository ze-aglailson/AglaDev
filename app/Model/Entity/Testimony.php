<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Testimony{


    /**
    * ID do depoimento
    *@var integer
    */
    public $id;

    /**
    * Nome do usuario que fex o depoimento
    *@var string
    */
    public $nome;

    /**
    * Mensagem do depoimento
    *@var string
    */
    public $mensagem ;    

    /**
    * Data de publicação do depoimento
    *@var string
    */
    public $data ;


    /**
    * Método responsavel por cadastrar a instancia atual no banco de dados
    * @return boolean
    */
    public function cadastrar(){
        //DEFINE A DATA 'pode ser definido no banco de dados tbm'
        $this->data = date('Y-m-d H:i:s');


        //INSERE O DEPOIMENTO NO BANCO DE DADOS
        $this->id = (new Database('depoimento'))->insert([
            'nome'      => $this->nome,
            'mensagem'  => $this->mensagem,
            'data'      => $this->data
        ]);

        //SUCESSO
        return true;
    }

    /**
    * Método responsavel por atualizar os dados do banco com a instancia atual
    * @return boolean
    */
    public function atualizar(){
    //ATUALIZA O DEPOIMENTO NO BANCO DE DADOS
        return (new Database('depoimento'))->update('id = '.$this->id,[
            'nome'      => $this->nome,
            'mensagem'  => $this->mensagem
        ]);

    }

    /**
    * Método responsavel por excluir depoimento do banco de dados 
    * @return boolean
    */
    public function excluir(){
        //EXCLUIR O DEPOIMENTO DO BANCO DE DADOS
            return (new Database('depoimento'))->delete('id = '.$this->id);
    
        }

    /**
     * Método responsavel por retornar um depoimento com base no seu id
     * @param integer $id
     * @return Testimony
    */
    public static function getTestimonyById($id){
        return self::getTestimonies('id = '.$id)->fetchObject(self::class);
    }

    /**
    * Método responsavel por retornar depoimentos
    * @param $where
    * @param $order
    * @param $limit
    * @param $fields  campos a serem retornados 
    * @return PDOStatement
    */
    public static function getTestimonies($where = null, $order = null, $limit = null, $fields='*'){
        return(new Database('depoimento'))->select($where,$order,$limit, $fields);
    }



}