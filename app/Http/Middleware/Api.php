<?php
//Responsavel por trocar o contentType de Router para application/json
namespace App\Http\Middleware;

class Api{

    /** 
     * Méodo responsável por exeutar o middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
    */
    public function handle($request, $next){
        //ALTERA O CONTETYPE PARA JSON
        $request->getRouter()->setContentType('application/json');

        //EXECUTA O PROXIMO NIVEL MIDDLEWARE 
        return $next($request);
    }

}