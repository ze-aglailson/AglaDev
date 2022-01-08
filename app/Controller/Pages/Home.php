<?php 

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Controller;
use App\Controller\Servico;
use \App\Model\Entity\Organization;

class Home extends Page{

    /**
     * Método responsável por retornar o conteudo (view) da página Home
     * @return string
     */

    public static function getHome($request){
        //ORGANIZAÇÃO
        $obOrganization = new Organization;

        //VIEW DA HOME
        $content =  View::render('pages/home/home', [
            'servicos'=> Servico::getServicos($request),
        ]);

        //RETORNA A PÁGINA

        return parent::getPage('Home | AglaDev','home',$content);
    }
}