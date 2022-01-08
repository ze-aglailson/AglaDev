<?php

namespace App\Controller;

use App\Utils\View;
use \App\Model\Entity\Servico as EntityServico;
use \WilliamCosta\DatabaseManager\Pagination;

class Servico{

    /** 
     * Método responsavél por obter a renderização dos itens de serviço
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
    */
    private static function getServicoItems($request, &$obPagination){

        //SERVIÇOS
        $itens = '';

        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityServico::getServicos(null, null, null, 'COUNT(*) AS qtd')->fetchObject()->qtd;

        //PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //INSTACIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 3);

        //RESULTADO QUE SERÃO RENDERIZADOS
        $result = EntityServico::getServicos(null, 'servicoCod DESC', $obPagination->getLimit());

        //RENDERIZA OS ITENS
        while($obServico = $result->fetchObject(EntityServico::class)){

            //VIEW DE SERVICO
            $itens .= View::render('pages/home/servicos/servico-item',[
                'nome'=> $obServico->servicoNome,
                'descricao' =>$obServico->servicoDescricao,
                'icon' =>$obServico->servicoIcon
            ]);
        }

        //RETORNA OS ITENS RENDERIZADOS
        return $itens;
    }

    /**
     * Método responsavel por retornar o conteudo 'view' de serviços
     * @param Request $request
     * @return string
    */
    public static function getServicos($request){
        
        //VIEW DA SECTION DE SERVICOS
        return $content = View::render('pages/home/servicos/servicos',[
            'itens' => self::getServicoItems($request, $obPagination)
        ]);
    }

}