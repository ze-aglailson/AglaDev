<?php 

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page{

    /**
     * Método responsável por obter a renderização dos itens de depimentos para a pagina
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getTestimonyItems($request, &$obPagination){
      //DEPOIMENTOS
      $itens = '';

      //QUANTIDADE TOTAL DE REGISTROS
      $quantidadeTotal = EntityTestimony::getTestimonies(null, null,null, 'COUNT(*) as qtd')->fetchObject()->qtd;
      
      //PAGINA ATUAL
      $queryParams = $request->getQueryParams();
      $paginaAtual = $queryParams['page'] ?? 1;

      //INSTACIA DE PAGINAÇÃO
      $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 3);

      //RESULTADOS QUE SERÃO RENDERIZADOS
      $result = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

      //RENDERIZA OS ITENS
      while($obTestimony = $result->fetchObject(EntityTestimony::class)){

          //VIEW DE DEPOIMENTO
          $itens .=  View::render('pages/testimony/item', [
            'nome'=> $obTestimony->nome,
            'mensagem'=>$obTestimony->mensagem,
            'data'=> date('d/m/Y H:i:s', strtotime($obTestimony->data))
        ]);
      


      }


      //RETORNA OS DEPOIMENTOS
      return $itens;
    }

    /**
     * Método responsável por retornar o conteudo (view) de depoimentos
     * @param Request $request
     * @return string
     */
     public static function getTestimonies($request){

        //VIEW DE DEPOIMENTOS E O FORMULARIO
        $content =  View::render('pages/testimonies', [
            'itens' => self::getTestimonyItems($request, $obPagination),
            'pagination'=>parent::getPagination($request, $obPagination)
        ]);

        //RETORNA A PÁGINA

        return parent::getPage('DEPOIMENTOS > AGLADEV', $content);
      }


    /**
     * Método responsável por cadastrar depoimentos
     * @param Request $request
     * @return string
     */
    public static function insertTestimony($request){
      //DADOS DO POST
      $postVars = $request->getPostVars();

      //NOVA INSTANCIA DE DEPOIMENTO
      $obTestimony = new EntityTestimony; //O ideal é valida se os dados realmente chegaram
      $obTestimony->nome = $postVars['nome'];
      $obTestimony->mensagem = $postVars['mensagem'];
      $obTestimony->cadastrar();

      //RETORNA A PAGINA DE LISTAGEM DE DEPOIMENTOS
      return self::getTestimonies($request);
    }
}