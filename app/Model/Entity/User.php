<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User{

    /**
    * ID do usuario
    *@var integer
    */
    public $id;

    /**
    * Nome do usuario
    *@var string
    */
    public $nome;

    /**
    * Email do usuario
    *@var string
    */
    public $email;

    /**
    * Senha do usuario
    *@var string
    */
    public $senha;

    /**
     * Método responsavel por cadastrar a instancia atual no banco de dados
     * @return boolean
    */
    public function cadastrar(){
        //INSERE A INSTACIA NO BANCO
        $this->id = (new Database('usuario'))->insert([
            'nome'  =>$this->nome,
            'email' =>$this->email,
            'senha' =>$this->senha
        ]);

        //SUCESSO
        return true;
    }

    /**
     * Método responsavel por atualizar os dados no banco
     * @return boolean
    */
    public function atualizar(){
        return (new Database('usuario'))->update('id = '.$this->id,[
            'nome'  =>$this->nome,
            'email' =>$this->email,
            'senha' =>$this->senha
        ]);
    }

    /**
     * Método responsavel por excluir os dados no banco
     * @return boolean
    */
    public function excluir(){
        return (new Database('usuario'))->delete('id = '.$this->id);
    }

    /**
     * Método responsavel por retorna uma instacia com base no seu ID
     * @param integer $id
     * @return User
    */
    public static function getUserById($id){    
        return self::getUsers('id = '.$id)->fetchObject(self::class);
    }


    /**
     * Método responsável por retornar um usuario com base no seu email
     * @param string $email
     * @return User
    */
    public static function getUserByEmail($email){
        return self::getUsers('email = "'.$email.'"')->fetchObject(self::class);
    }

    /**
    * Método responsavel por retornar usuários
    * @param $where
    * @param $order
    * @param $limit
    * @param $fields  campos a serem retornados 
    * @return PDOStatement
    */
    public static function getUsers($where = null, $order = null, $limit = null, $fields='*'){
        return(new Database('usuario'))->select($where,$order,$limit, $fields);
    }

}
