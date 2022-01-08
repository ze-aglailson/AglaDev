<?php

namespace App\Controller;

use App\Utils\View;
use \App\Model\Entity\Servico as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Servico{

    /** 
     * Método responsavél por obter a renderização dos itens de serviço
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
    */
    private static function getServicoItems($request, &$obPagination){

        return 'item';
    }

    /**
     * Método responsavel por retornar o conteudo 'view' de serviços
     * @param Request $request
     * @return string
    */
    public static function getServicos($request){
        
        //VIEW DA SECTION DE SERVICOS
        return $content = View::render('pages/home/servicos/servicos');
    }

}