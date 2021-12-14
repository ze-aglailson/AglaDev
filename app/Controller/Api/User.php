<?php

namespace App\Controller\Api;

use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Api{

   /**
     * Método responsável por obter a renderização dos itens de usuarios para a pagina
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getUserItems($request, &$obPagination){
        //USUARIOS
        $itens = [];
  
        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityUser::getUsers(null, null,null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        
        //PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;
  
        //INSTACIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);
  
        //RESULTADOS QUE SERÃO RENDERIZADOS
        $result = EntityUser::getUsers(null, 'id ASC', $obPagination->getLimit());
  
        //RENDERIZA OS ITENS
        while($obUser = $result->fetchObject(EntityUser::class)){
            $itens[] = [
              'id'      =>(int)$obUser->id,
              'nome'    => $obUser->nome,
              'email'   => $obUser->email
          ];
        }

        //RETORNA OS USUARIOS
        return $itens;
    }


    /**
     * Método responsável por retornar os usuarios cadastrados
     * @param  Request $request
     * @return array
    */
    public static function getUsers($request){
        return [
            'usuarios'   =>self::getUserItems($request, $obPagination),
            'paginacao'  =>parent::getPagination($request, $obPagination)
        ];
    }

    /**
     * Método responsável por retornar os detalhes de um usuario
     * @param  Request $request
     * @param  integer $id
     * @return array
    */
    public static function getUser($request, $id){
        //VERIFICA SE O ID DO USUARIO NÃO É NUMÉRICO, 0 OU MENOR QUE 0
        if(!is_numeric($id) || $id<=0){
            throw new \Exception("O id '".$id."' não é válido", 400);
        }
        
        //BUSCA O USUARIO
        $obUser = EntityUser::getUserById($id);

        //VALIDA SE O USUARIO EXISTE
        if(!$obUser instanceof EntityUser){
            throw new \Exception("O usuário ".$id." não foi encontrado", 404);
            
        }

        //RETORNA OS DETALHES DE UM USUARIO
        return [
            'id'      =>(int)$obUser->id,
            'nome'    => $obUser->nome,
            'email'   => $obUser->email
        ];
    }

    /**
     * Método responsavel por retornar os dados do usuario atualmente autenticado
     * @param Request $request
     * @return array
    */
    public static function getCurrentUser($request){
        //USUARIO ATUAL
        $obUser = $request->user;

        //RETORNA OS DETALHES DE UM USUARIO
        return [
            'id'      =>(int)$obUser->id,
            'nome'    => $obUser->nome,
            'email'   => $obUser->email
        ];
    }

    /**
     * Método responsvel por cadatrar um novo usuario
     * @param Request $request
    */
    public static function setNewUser($request){
        //POST VARS
        $postVars = $request->getPostVars();
        
        //VERIFICA OS CAMPOS OBRIGATORIOS
        if(!isset($postVars['nome']) || !isset($postVars['email']) || !isset($postVars['senha'])){
            throw new \Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios", 400);
        }

        //VALIDA A DUPLICAÇÃO DE USUSARIOS
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if($obUserEmail instanceof EntityUser){
            throw new \Exception("O e-mail '".$postVars['email']."' já está e uso", 400);
        }

        //NOVO USUARIO
        $obUser = new EntityUser;
        $obUser->nome   = $postVars['nome'];
        $obUser->email  = $postVars['email'];
        $obUser->senha  = password_hash($postVars['senha'], PASSWORD_DEFAULT);
        $obUser->cadastrar();

        //RETORNA OS DETALHES DO USUARIO CADASTRADO
        return [
            'id'      =>(int)$obUser->id,
            'nome'    => $obUser->nome,
            'email'   => $obUser->email
        ];
    }

    /**
     * Método responsvel por atualizar um usuario
     * @param Request $request
     * @param integer $id
    */
    public static function setEditUser($request, $id){
       //POST VARS
       $postVars = $request->getPostVars();
        
       //VERIFICA OS CAMPOS OBRIGATORIOS
       if(!isset($postVars['nome']) || !isset($postVars['email']) || !isset($postVars['senha'])){
           throw new \Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios", 400);
       }

        //BUSCA O USUARIO
        $obUser = EntityUser::getUserById($id);

        //VALIDA SE O USUARIO EXISTE
        if(!$obUser instanceof EntityUser){
            throw new \Exception("O usuário ".$id." não foi encontrado", 404);
            
        }

       //VALIDA A DUPLICAÇÃO DE USUSARIOS
       $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
       if($obUserEmail instanceof EntityUser && $obUserEmail->id != $obUser->id){
           throw new \Exception("O e-mail '".$postVars['email']."' já está e uso", 400);
       }

       //ATUALIZA USUARIO
       $obUser->nome   = $postVars['nome'];
       $obUser->email  = $postVars['email'];
       $obUser->senha  = password_hash($postVars['senha'], PASSWORD_DEFAULT);
       $obUser->atualizar();

       //RETORNA OS DETALHES DO USUARIO CADASTRADO
       return [
           'id'      =>(int)$obUser->id,
           'nome'    => $obUser->nome,
           'email'   => $obUser->email
       ];
    }

    /**
     * Método responsvel por deletar um usuario
     * @param Request $request
     * @param integer $id
    */
    public static function setDeleteUser($request, $id){
        //BUSCA O USUARIO
        $obUser = EntityUser::getUserById($id);

        //VALIDA SE O USUARIO EXISTE
        if(!$obUser instanceof EntityUser){
            throw new \Exception("O usuário ".$id." não foi encontrado", 404);
            
        }

        //IMPEDE A EXCLUSÃO DO PROPRIO USUARIO
        if($obUser->id == $request->user->id){
            throw new \Exception("Não é possível excluir o cadastro atualmente conectado", 400);
        }

        //EXLUIR O USUARIO
        $obUser->excluir();

        //RETORNA O SUCESSO DA EXCLUSÃO
        return [
            'sucesso' => true
        ];
    }

}