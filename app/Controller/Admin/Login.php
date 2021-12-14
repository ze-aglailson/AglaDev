<?php

namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\User;
use App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page{

    /**
     * Método responsavel por retorna a renderização da pagina de login
     * @param  Request $request
     * @param string $errorMessage
     * @return string
    */
    public static function getLogin($request, $errorMessage=null){

        //STATUS
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        //COTEUDO DA PÁGINA DE LOGIN
        $content = View::render('admin/login',[
            'status' => $status
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('Login > WDEV', $content);
    }

    /**
     * Método responsavel por definir o login do usuario
     * @param  Request $request
     * 
    */
    public static function setLogin($request){
        //POST VARS
        $postVars = $request->getPostVars();
        $email    = $postVars['email'] ?? '';
        $senha    = $postVars['senha'] ?? '';

        //BUSCA USUARIO PELO EMAIL
        $obUser = User::getUserByEmail($email);

        if(!$obUser instanceof User){
            return self::getLogin($request, 'E-mail ou senha inválido');
        }

        //VERIFICA A SENHA DO USUARIO
        if(!password_verify($senha, $obUser->senha)){
            return self::getLogin($request, 'E-mail ou senha inválido');
        }

        //CRIA A SESSÃO DE LOGIN DO USUARIO
        SessionAdminLogin::login($obUser);

        //REDIRECIONA O USUARIO PARA A HOME DO ADMIN
        $request->getRouter()->redirect('/admin');
    }

    public static function setLogout($request){
        //DESTROY A SESSÃO DE LOGIN DO USUARIO
        SessionAdminLogin::logout();

        //REDIRECIONA O USUARIO PARA A TELA DE LOGIN
        $request->getRouter()->redirect('/admin/login');
    }

}