<?php

namespace App\Controller\Admin;

use App\Utils\View;

class Home extends Page{

    /**
     * Método responsavel por renderizar a view de home do painel
     * @param Request $request
     * @return string
    */
    public static function getHome($request){
        //CONTEUDA DA HOME
        $content = View::render('admin/modules/home/index');

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Home > Agladev', $content,'home');

    } 

}