<?php
//Controller responsável pela rota da rai da api e a classe mãe das demais controllers

namespace App\Controller\Api;

class Api{

    /**
     * Método responsável por retornar os detalhes da API
     * @param  Request $request
     * @return array
    */
    public static function getDetails($request){
        return [
            'nome'  =>'API - AglaDev',
            'versao'=>'v1.0.0',
            'autor' =>'José Aglailson',
            'email' =>'jaglailson1@gmail.com'
        ];
    }

    /**
     * Método responsável por retornar os detalhes da paginação
     * @param  Request $request
     * @param  Pagination $obPagination
     * @return array
    */
    protected static function getPagination($request, $obPagination){
        //QUERY PARAMS
        $queryParams = $request->getQueryParams();

        
        //PAGINA
        $pages = $obPagination->getPages();
        
        //RETORNO
        return [
            'paginaAtual'       => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
            'quantidadePaginas' => !empty($pages) ? count($pages) : 1
        ];
    }

}