<?php 

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class About extends Page{

    /**
     * Método responsável por retornar o conteudo (view) da página sobre
     * @return string
     */

     public static function getAbout(){
        //ORGANIZAÇÃO
        $obOrganization = new Organization;

        //VIEW DA HOME
        $content =  View::render('pages/about', [
            'name'=> $obOrganization->name,
            'descricao'=>$obOrganization->description,
            'site'=> $obOrganization->site
        ]);

        //RETORNA A PÁGINA

        return parent::getPage('SOBRE > AGLADEV', $content);
     }
}