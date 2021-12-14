<?php
//Responsavel verificar a autenticação do usuário em rotas da api com o jwt
namespace App\Http\Middleware;

use \App\Model\Entity\User;
use \Firebase\JWT\JWT;

class JWTAuth{

    /**
     * Método responsavel por retornar uma instancia de usuarios autenticado
     * @param Request $request
     * @return User
    */
    private function getJWTAuthUser($request){
        //HEADERS
        $headers = $request->getHeaders();
        
        //TOKEN PURO JWT
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ','', $headers['Authorization']) : '';

        try {
            //DECODE DO TOKEN
            $decode = (array)JWT::decode($jwt, getenv('JWT_KEY'),['HS256']);
            
        } catch (\Exception $e) {
            throw new \Exception("Token inválido", 403);
            
        }


        //VERIFICA O EMAIL DO TOKEN
        $email = $decode['email'] ?? '';


        //BUSCA USUARIO PELO O E-MAIL
        $obUser = User::getUserByEmail($email);

        //RETORNA USUARIO
        return $obUser instanceof User ? $obUser : false;
    }

    /**
     * Método responsavel por validar o acesso via JWT
     * @param Request $request
    */
    private function auth($request){
        //VERIFICA O USUARIO RECEBIDO
        if($obUser = $this->getJWTAuthUser($request)){
            $request->user = $obUser;
            return true;
        }

        //EMITE O ERRO DE SENHA INVÁLIDA
        throw new \Exception("Acesso negado", 403);
        
    }

    /** 
     * Méodo responsável por exeutar o middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
    */
    public function handle($request, $next){
        //REALIZA A VALIDAÇÃO DO ACESSO VIA JWT
        $this->auth($request);

        //EXECUTA O PROXIMO NIVEL MIDDLEWARE 
        return $next($request);
    }

}