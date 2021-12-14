<?php
//Controller responsavel pela autenticação jwt

namespace App\Controller\Api;

use \App\Model\Entity\User;
use  Firebase\JWT\JWT;

class Auth extends Api{

    /**
     * Método responsavel por gerar um token JWT
     * @param $request
     * @return array
    */
    public static function generateToken($request){
        //POST VARS
        $postVars = $request->getPostVars();

        //VALIDA OS CAMPOS OBRIGATORIOS
        if(!isset($postVars['email']) || !isset($postVars['senha'])){
            throw new \Exception("Os campos 'email' e 'senha' são obrigatórios ", 400);
        }

        //BUSCA USUARIO PELO E-MAIL
        $obUser = User::getUserByEmail($postVars['email']);
        if(!$obUser instanceof User){
            throw new \Exception("O usuário ou senha são inválidos", 400);
        }

        //VALIDA A SENHA DO USUARIO
        if(!password_verify($postVars['senha'], $obUser->senha)){
            throw new \Exception("O usuário ou senha são inválidos", 400);
        }

        //PAYLOAD 'o email do usuario será usado para gerar o token'
        $payload = [
            'email'=> $obUser->email
        ];

        //RETORNA O TOKEN GERADO
        return[
            'token' => JWT::encode($payload, getenv('JWT_KEY'))
        ];
    }

}