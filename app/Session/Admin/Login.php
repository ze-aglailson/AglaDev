<?php

namespace App\Session\Admin;

class Login{

    /**
     * Método responsavel por iniciar a sessão
    */
    private static function init(){

        //VERIFICA SE A SESSÃO NÃO ESTA ATIVA
        if(session_start() != PHP_SESSION_ACTIVE){

            session_start();

        }

    }

    /**
     * Método responsavel por criar o login do usuário
     * @param User $obuser
     * @return boolean
    */
    public static function login($obuser){
        //INICIA A SESSÃO
        self::init();

        //DEFINE A SESSÃO DO USUARIO
        $_SESSION['admin']['usuario']=[
            'id'=>$obuser->id,
            'nome'=>$obuser->nome,
            'email'=>$obuser->email
        ];

        //SUCESSO SESSÃO CRIADA
        return true;
    }
    /**
     * Método responsavel por verificar se o usuario esta logado
     * @return boolean
    */
    public static function isLogged(){
        //INICIA A SESSÃO
        self::init();

        //RETORNA A VERFICAÇÃO
        return isset($_SESSION['admin']['usuario']['id']);

    }

    /**
     * Método responsavel por executar o logout de usuario
     * @return boolean
    */
    public static function logout(){
        //INICIA A SESSÃO
        self::init();

        //DESLOGA O USUARIO
        unset($_SESSION['admin']['usuario']);

        //SUCESSO USUARIO DESLOGADO
        return true;
    }

}