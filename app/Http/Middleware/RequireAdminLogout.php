<?php
/* 
   Verifica se o usuario esta deslogado ao tentar acessar 
   a tela de login, caso sim pode acesar a tela, caso não
   redireciona para a home de admin
*/

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogout{

    /** 
     * Méodo responsável por exeutar o middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
    */
    public function handle($request, $next){

        //VERIFICA SE O USUARIO ESTA LOGADO
        if(SessionAdminLogin::isLogged()){
            $request->getRouter()->redirect('/admin');
        }

        //COTINUA A EXECUÇÃO
        return $next($request);

    }

    

}