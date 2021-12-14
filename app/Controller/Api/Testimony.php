<?php

namespace App\Controller\Api;

use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Api{

   /**
     * Método responsável por obter a renderização dos itens de depimentos para a pagina
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getTestimonyItems($request, &$obPagination){
        //DEPOIMENTOS
        $itens = [];
  
        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityTestimony::getTestimonies(null, null,null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        
        //PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;
  
        //INSTACIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);
  
        //RESULTADOS QUE SERÃO RENDERIZADOS
        $result = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());
  
        //RENDERIZA OS ITENS
        while($obTestimony = $result->fetchObject(EntityTestimony::class)){
            $itens[] = [
              'id'      =>(int)$obTestimony->id,
              'nome'    => $obTestimony->nome,
              'mensagem'=>$obTestimony->mensagem,
              'data'    => $obTestimony->data
          ];
        }

        //RETORNA OS DEPOIMENTOS
        return $itens;
    }


    /**
     * Método responsável por retornar os depoimentos cadastrados
     * @param  Request $request
     * @return array
    */
    public static function getTestimonies($request){
        return [
            'depoimentos'   =>self::getTestimonyItems($request, $obPagination),
            'paginacao'     =>parent::getPagination($request, $obPagination)
        ];
    }

    /**
     * Método responsável por retornar oos detalhes de um depoimento
     * @param  Request $request
     * @param  integer $id
     * @return array
    */
    public static function getTestimony($request, $id){
        //VERIFICA SE O ID DO DEPOIMENTO NÃO É NUMÉRICO
        if(!is_numeric($id) || $id<=0){
            throw new \Exception("O id '".$id."' não é válido", 400);
        }
        
        //BUSCA DEPOIMENTO
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA SE O DEPOIMENTO EXISTE
        if(!$obTestimony instanceof EntityTestimony){
            throw new \Exception("O depoimento ".$id." não foi encontrado", 404);
            
        }

        //RETORNA OS DETALHES DE UM DEPOIMENTO
        return [
            'id'      =>(int)$obTestimony->id,
            'nome'    => $obTestimony->nome,
            'mensagem'=>$obTestimony->mensagem,
            'data'    => $obTestimony->data
        ];
    }

    /**
     * Método responsvel por cadatrar um novo usuario
     * @param Request $request
    */
    public static function setNewTestimony($request){
        //POST VARS
        $postVars = $request->getPostVars();
        
        //VERIFICA OS CAMPOS OBRIGATORIOS
        if(!isset($postVars['nome']) || !isset($postVars['mensagem'])){
            throw new \Exception("Os campos 'nome' e 'mensagem' são obrigatórios", 400);
        }

        //NOVO DEPOIMENTO
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();

        //RETORNA OS DETALHES DO DEPOIMENTO CADASTRADO
        return [
            'id'      =>(int)$obTestimony->id,
            'nome'    => $obTestimony->nome,
            'mensagem'=>$obTestimony->mensagem,
            'data'    => $obTestimony->data
        ];
    }

    /**
     * Método responsvel por atualizar um depoimento
     * @param Request $request
     * @param integer $id
    */
    public static function setEditTestimony($request, $id){
        //POST VARS
        $postVars = $request->getPostVars();
        
        //VERIFICA OS CAMPOS OBRIGATORIOS
        if(!isset($postVars['nome']) || !isset($postVars['mensagem'])){
            throw new \Exception("Os campos 'nome' e 'mensagem' são obrigatórios", 400);
        }

        //BUSCA O DEPOIMENTO NO BANCO
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA DEPOIMENT
        if(!$obTestimony instanceof EntityTestimony){
            throw new \Exception("O depoimento ".$id." não foi encontrado", 404);
            
        }

        //ATUALIZA O DEPOIMENTO
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->atualizar();

        //RETORNA OS DETALHES DO DEPOIMENTO ATUALIZADOS
        return [
            'id'      =>(int)$obTestimony->id,
            'nome'    => $obTestimony->nome,
            'mensagem'=>$obTestimony->mensagem,
            'data'    => $obTestimony->data
        ];
    }

    /**
     * Método responsvel por deletar um usuario
     * @param Request $request
     * @param integer $id
    */
    public static function setDeleteTestimony($request, $id){
        //BUSCA O DEPOIMENTO NO BANCO
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA DEPOIMENT
        if(!$obTestimony instanceof EntityTestimony){
            throw new \Exception("O depoimento ".$id." não foi encontrado", 404);
            
        }

        //EXLUIR DEPOIMENTO
        $obTestimony->excluir();

        //RETORNA O SUCESSO DA EXCLUSÃO
        return [
            'sucesso' => true
        ];
    }

}