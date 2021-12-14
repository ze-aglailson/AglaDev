<?php
//Responsavel verificar a autenticação do usuário em rotas da api
namespace App\Http\Middleware;

use \App\Model\Entity\User;

class UserBasicAuth{

    /**
     * Método responsavel por retornar uma instancia de usuarios autenticado
     * @return User
    */
    private function getBasicAuthUser(){
        //VERIFICA A EXISTENCIA DOS DADOS DE ACESSO
        if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])){
            return false;
        }

        //BUSCA USUARIO PELO O E-MAIL
        $obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);

        //VERIFICA A INSTANCIA
        if(!$obUser instanceof User){
            return false;
        }

        //VALIDA SENHA E RETORNA O USUARIO
        return password_verify($_SERVER['PHP_AUTH_PW'], $obUser->senha) ? $obUser : false;
    }

    /**
     * Método responsavel por validar o acesso via HTTPT BASIC AUTH
     * @param Request $request
    */
    private function basicAuth($request){
        //VERIFICA O USUARIO RECEBIDO
        if($obUser = $this->getBasicAuthUser()){
            $request->user = $obUser;
            return true;
        }

        //EMITE O ERRO DE SENHA INVÁLIDA
        throw new \Exception("Usuário ou senha inválidos", 403);
        
    }

    /** 
     * Méodo responsável por exeutar o middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
    */
    public function handle($request, $next){
        //REALIZA A VALIDAÇÃO DO ACESSO VIA BASIC AUTH
        $this->basicAuth($request);

        //EXECUTA O PROXIMO NIVEL MIDDLEWARE 
        return $next($request);
    }

}