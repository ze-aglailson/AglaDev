<?php

namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Page{


/**
     * Método responsável por obter a renderização dos itens de usuarios para a pagina
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getUserItems($request, &$obPagination){
        //USUÁRIOS
        $itens = '';
  
        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityUser::getUsers(null, null,null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        

        //PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;
  
        //INSTACIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);
  
        //RESULTADOS QUE SERÃO RENDERIZADOS
        $result = EntityUser::getUsers(null, 'id DESC', $obPagination->getLimit());
  
        //RENDERIZA OS ITENS
        while($obUser = $result->fetchObject(EntityUser::class)){
            $itens .=  View::render('admin/modules/users/item', [
              'id'    => $obUser->id,
              'nome'  => $obUser->nome,
              'email' => $obUser->email
          ]);
        
  
  
        }
  
  
        //RETORNA OS DEPOIMENTOS
        return $itens;
      }

    /**
     * Método responsavel por renderizar a view de listagem de usuarios
     * @param Request $request
     * @return string
    */
    public static function getUsers($request){
        //CONTEUDA DA HOME
        $content = View::render('admin/modules/users/index',[
            'itens'     => self::getUserItems($request,$obPagination),
            'pagination'=> parent::getPagination($request,$obPagination),
            'status'    => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Usuários > Agladev', $content,'users');

    }

    /**
     * Método responsavel por retornar o formulario de cadastro de um novo usuario
     * @param Request $request
     * @return string
    */
    public static function getNewUser($request){

        //CONTEUDA DO FORMULARIO
        $content = View::render('admin/modules/users/form',[
          'title'   =>'Cadastrar usuário',
          'nome'    =>'',
          'email'   =>'',
          'status'  =>self::getStatus($request)
        ]);

      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel('Cadastrar usuário > Agladev', $content,'users');

    }

    /**
     * Método responsavel por cadastrar um usuario no banco de dados
     * @param Request $request
     * @return string
    */
    public static function setNewUser($request){

      //POST VARS
      $postVars = $request->getPostVars();
      $nome = $postVars['nome'];
      $email = $postVars['email'];
      $senha = $postVars['senha'];

      //VALIDA O E-MAIL DO NOVO USUÁRIO
      $obUser = EntityUser::getUserByEmail($email);
      if($obUser instanceof EntityUser){
        //REDIRECIONA O USUÁRIO COM O STATUS QUE O EMAIL JÁ ESTA SENDO UTILIZADO
        $request->getRouter()->redirect('/admin/users/new?status=duplicated');

      }

      //NOVA INSTACIA DE USUARIO
      $obUser = new EntityUser;
      $obUser->nome   = $nome;
      $obUser->email  = $email;
      $obUser->senha  = password_hash($senha, PASSWORD_DEFAULT);
      $obUser->cadastrar();

      //REDIRECIONA O USUARIO
      $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=created');
    
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
          return Alert::getSuccess('Usuário criado com sucesso!');
        break;
        case 'updated':
          return Alert::getSuccess('Usuário atualizado com sucesso!');
        break;
        case 'deleted':
          return Alert::getSuccess('Usuário excluido com sucesso!');
        break;
        case 'duplicated':
          return Alert::getError('O e-mail digitado já esta sendo utilizado por outro usuário!');
        break;
      }
    }

    /**
     * Método responsavel por retornar o formulario de edição de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
    */
    public static function getEditUser($request, $id){
      //OBTEM O USUARIO DO BANCO DE DADOS
      $obUser = EntityUser::getUserById($id);

      //VALIDA A INSTACIA
      if(!$obUser instanceof EntityUser){
        $request->getRouter()->redirect('/admin/users');
      }
      
      //CONTEUDO DO FORMULARIO
      $content = View::render('admin/modules/users/form',[
        'title'   =>'Editar usuário',
        'nome'    =>$obUser->nome,
        'email'   =>$obUser->email,
        'status'  => self::getStatus($request)
      ]);
    
    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('Editar usuário > Agladev', $content,'users');

  }

    /**
     * Método responsavel por gravar a atualiazação de  um usuario
     * @param Request $request
     * @param integer $id
     * @return string
    */
    public static function setEditUser($request, $id){
      //OBTEM O USUARIO DO BANCO DE DADOS
      $obUser = EntityUser::getUserById($id);

      //VALIDA A INSTACIA
      if(!$obUser instanceof EntityUser){
        $request->getRouter()->redirect('/admin/users');
      }


      //POST VARS
      $postVars = $request->getPostVars();

      $nome = $postVars['nome'];
      $email = $postVars['email'];
      $senha = $postVars['senha'];

      //VALIDA O E-MAIL DO NOVO USUÁRIO
      $obUserEmail = EntityUser::getUserByEmail($email);
      if($obUserEmail instanceof EntityUser && $obUserEmail->id != $id){
        //REDIRECIONA O USUÁRIO COM O STATUS QUE O EMAIL JÁ ESTA SENDO UTILIZADO
        $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');

      }

      //ATUALIZA A INSTANCIA

      $obUser->nome  = $nome;
      $obUser->email = $email;
      $obUser->senha = password_hash($senha,PASSWORD_DEFAULT);
      $obUser->atualizar();

      //REDIRECIONA O USUARIO
      $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=updated');

  }

    /**
     * Método responsavel por retornar o formulario de exclusão de um usuario
     * @param Request $request
     * @param integer $id
     * @return string
    */
    public static function getDeleteUser($request, $id){
      //OBTEM O USUARIO DO BANCO DE DADOS
      $obUser = EntityUser::getUserById($id);

      //VALIDA A INSTACIA
      if(!$obUser instanceof EntityUser){
        $request->getRouter()->redirect('/admin/users');
      }
      
      //CONTEUDO DO FORMULARIO
      $content = View::render('admin/modules/users/delete',[
        'nome'    => $obUser->nome,
        'email'   => $obUser->email
      ]); 
    
    //RETORNA A PÁGINA COMPLETA
    return parent::getPanel('Excluir usuário > Agladev', $content,'users');

  }

   /**
     * Método responsavel por deletar um usuario
     * @param Request $request
     * @param integer $id
     * @return string
    */
    public static function setDeleteUser($request, $id){
      //OBTEM O USUARIO DO BANCO DE DADOS
      $obUser = EntityUser::getUserById($id);

      //VALIDA A INSTACIA
      if(!$obUser instanceof EntityUser){
        $request->getRouter()->redirect('/admin/users');
      }

      //EXCLUI O USUÁRIO
      $obUser->excluir();

      //REDIRECIONA O USUARIO
      $request->getRouter()->redirect('/admin/users?status=deleted');

  }

}