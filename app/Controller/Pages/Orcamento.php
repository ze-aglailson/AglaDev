<?php 

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Orcamento extends Page{

    /**
     * Método responsável por retornar o conteudo (view) da página de orçamento
     * @return string
     */

     public static function getOrcamento(){
        //ORGANIZAÇÃO
        $obOrganization = new Organization;

        //VIEW DA HOME
        $content =  View::render('pages/orcamento', [
            'name'=> $obOrganization->name,
            'descricao'=>$obOrganization->description,
            'site'=> $obOrganization->site
        ]);

        //RETORNA A PÁGINA

        return parent::getPage('Orçamento | AglaDev','orcamento',$content);
     }
}