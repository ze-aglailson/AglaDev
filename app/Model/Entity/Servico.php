<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager;
use WilliamCosta\DatabaseManager\Database;

class Servico{

    /**
     * ID do serviço
     * @var integer
    */
    public $servicoCod;

    /**
     * Nome do serviço
     * @var string
    */
    public $servicoNome;

    /**
    * Descrição do serviço
    * @var string
    */
    public $servicoDescricao;

    /**
    * Icone do serviço
    * @var string
    */
    public $servicoIcon;

    /**
     * Método responsavel por cadastrar a instancia atual no banco de dados
     * @return boolean
    */
    public function cadastrar(){

        //INSERE O SERVICO NO BANCO DE DADOS
        $this->servicoCod = (new Database('Servico'))->insert([
            'servicoNome'       => $this->servicoNome,
            'servicoDescricao'  => $this->servicoDescricao,
            'servicoIcon'       => $this->servicoIcon
        ]);
    }

    /**
     * Método responsavel por atualizar os dados no banco 'da instancia atual'
     * @return boolean
    */
    public function atualizar(){
        //ATUALIZA O SERVICO NO BANCO
        return (new Database('Servico'))->update('servicoCod = '.$this->servicoCod,[
            'servicoNome'       =>$this->servicoNome,
            'servicoDescricao'  =>$this->servicoDescricao,
            'servicoIcon'       =>$this->servicoIcon
        ]);
    }

    /**
     * Método responsavel por excluir serviço do banco
     * @return boolean
    */
    public function excluir(){
        //EXCLUI O SERVICO DO BANCO
        return (new Database('Servico'))->delete('id = '.$this->servicoCod);
    }

    /**
     * Método responsavel por retorna um servico com base no seu id
     * @param integer $servicoCod
     * @return Servico
    */
    public static function getServicoById($servicoCod){
        return self::getServicos('servicoCod = '.$servicoCod)->fetchObject(self::class);
    }

    /**
     * Método responsavel por retornar os serviços
     * @param $where
     * @param $order
     * @param $limit
     * @param $fields campos a serem retornados
     * @return PDOStatement
    */
    public static function getServicos($where = null, $order = null, $limit = null, $fields = '*'){
        return(new Database('Servico'))->select($where, $order, $limit, $fields);
    }
}