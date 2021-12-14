<?php

namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;
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
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);
  
        //RESULTADOS QUE SERÃO RENDERIZADOS
        $result = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());
  
        //RENDERIZA OS ITENS
        while($obTestimony = $result->fetchObject(EntityTestimony::class)){
            //VIEW DE DEPOIMENTO
            $itens .=  View::render('admin/modules/testimonies/item', [
              'id'=>$obTestimony->id,
              'nome'=> $obTestimony->nome,
              'mensagem'=>$obTestimony->mensagem,
              'data'=> date('d/m/Y H:i:s', strtotime($obTestimony->data))
          ]);
        
  
  
        }
  
  
        //RETORNA OS DEPOIMENTOS
        return $itens;
      }

    /**
     * Método responsavel por renderizar a view de listagem de depoimentos
     * @param Request $request
     * @return string
    */
    public static function getTestimonies($request){
        //CONTEUDA DA HOME
        $content = View::render('admin/modules/testimonies/index',[
            'itens'     => self::getTestimonyItems($request,$obPagination),
            'pagination'=> parent::getPagination($request,$obPagination),
            'status'    => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Depoimentos > Agladev', $content,'testimonies');

    }

    /**
     * Método responsavel por retornar o formulario de cadastro de um novo depoimento
     * @param Request $request
     * @return string
    */
    public static function getNewTestimony($request){

        //CONTEUDA DO FORMULARIO
        $content = View::render('admin/modules/testimonies/form',[
          'title'   =>'Cadastrar depoimento',
          'nome'    =>'',
          'mensagem'=>'',
          'status'  =>''
        ]);

      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel('Cadastrar depoimento > Agladev', $content,'testimonies');

    }

    /**
     * Método responsavel por cadastrar depoimento no banco de dados
     * @param Request $request
     * @return string
    */
    public static function setNewTestimony($request){

      //POST VARS
      $postVars = $request->getPostVars();

      //NOVA INSTACIA DE DEPOIMENTOS
      $obTestimony = new EntityTestimony;
      $obTestimony->nome     = $postVars['nome'] ?? '';
      $obTestimony->mensagem = $postVars['mensagem'] ?? '';
      $obTestimony->cadastrar();

      //REDIRECIONA O USUARIO
      $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=created');
    
    }
    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
    */
    public static function getStatus($request){
      //QUERY PARAMS
      $queryParams = $request->getQueryParams();
      
      //STATUS 
      if(!isset($queryParams['status'])) return '';

      //MENSAGENS DE STATUS
      switch ($queryParams['status']) {
        case 'created':
          return Alert::getSuccess('Depoimento criado com sucesso!');
        break;
        case 'updated':
          return Alert::getSuccess('Depoimento atualizado com sucesso!');
        break;
        case 'updated':
          return Alert::getSuccess('Depoimento excluido com sucesso!');
        break;

      }
    }

    /**
     * Método responsavel por retornar o formulario de edição de um novo depoimento
     * @param Request $request
     * @param integer $id
     * @return string
    */
    public static function getEditTestimony($request, $id){
      //OBTEM O DEPOIMENTO DO BANCO DE DADOS
      $obTestimony = EntityTestimony::getTestimonyById($id);

      //VALIDA A INSTACIA
      if(!$obTestimony instanceof EntityTestimony){
        $request->getRouter()->redirect('/admin/testimonies');
      }
      
      //CONTEUDO DO FORMULARIO
      $content = View::render('admin/modules/testimonies/form',[
        'title'   =>'Editar depoimento',
        'nome'    =>$obTestimony->nome,
        'mensagem'=>$obTestimony->mensagem,
        'status'  => self::getStatus($request)
      ]);
    
    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('Editar depoimento > Agladev', $content,'testimonies');

  }

    /**
     * Método responsavel por gravar a atualiazação de  um depoimento
     * @param Request $request
     * @param integer $id
     * @return string
    */
    public static function setEditTestimony($request, $id){
      //OBTEM O DEPOIMENTO DO BANCO DE DADOS
      $obTestimony = EntityTestimony::getTestimonyById($id);

      //VALIDA A INSTACIA
      if(!$obTestimony instanceof EntityTestimony){
        $request->getRouter()->redirect('/admin/testimonies');
      }

      //POST VARS
      $postVars = $request->getPostVars();

      //ATUALIZA A INSTANCIA

      $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
      $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;
      $obTestimony->atualizar();

      //REDIRECIONA O USUARIO
      $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=updated');

  }

    /**
     * Método responsavel por retornar o formulario de exclusão de um depoimento
     * @param Request $request
     * @param integer $id
     * @return string
    */
    public static function getDeleteTestimony($request, $id){
      //OBTEM O DEPOIMENTO DO BANCO DE DADOS
      $obTestimony = EntityTestimony::getTestimonyById($id);

      //VALIDA A INSTACIA
      if(!$obTestimony instanceof EntityTestimony){
        $request->getRouter()->redirect('/admin/testimonies');
      }
      
      //CONTEUDO DO FORMULARIO
      $content = View::render('admin/modules/testimonies/delete',[
        'nome'    =>$obTestimony->nome,
        'mensagem'=>$obTestimony->mensagem
      ]);
    
    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('Excluir depoimento > Agladev', $content,'testimonies');

  }

   /**
     * Método responsavel por deletar um depoimento
     * @param Request $request
     * @param integer $id
     * @return string
    */
    public static function setDeleteTestimony($request, $id){
      //OBTEM O DEPOIMENTO DO BANCO DE DADOS
      $obTestimony = EntityTestimony::getTestimonyById($id);

      //VALIDA A INSTACIA
      if(!$obTestimony instanceof EntityTestimony){
        $request->getRouter()->redirect('/admin/testimonies');
      }

      //EXCLUI UM DEPOIMENTO
      $obTestimony->excluir();

      //REDIRECIONA O USUARIO
      $request->getRouter()->redirect('/admin/testimonies?status=deleted');

  }

}