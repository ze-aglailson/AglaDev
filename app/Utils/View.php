<?php

namespace App\Utils;

class View{


    /** 
     * Variaveis padrões da view
     * @var array
    */
    private static $vars =[];


    /**
     * Método responsavel por definir os dados iniciais da classe
     * @param array $vars
     * 
     */
    public static function init($vars = []){
        self::$vars = $vars;
    }

    /**
     * Método responsavel por retornar o conteudo de uma view
     * @param string $view nome da view a ser retornada
     * @return string
     */

    public static function getContentView($view){
        $file = __DIR__.'/../../resources/views/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Método responsavel por retornar o conteudo renderizado de uma view já com suas variaveis
     * @param string $view nome da view a ser renderizada
     * @param array  $vars (string/numeric) váriaveis que serão adicionadas dinamicamente na view
     * @return string
     */

     public static function render($view, $vars = []){
        //CONTEUDO DA VIEW
        $contentView = self::getContentView($view);

        //MERGE DE VARIAVIES DA VIEW 'unindo array'
        $vars = array_merge(self::$vars, $vars);

        //CHAVES DO ARRAY DE VÁRIAVEIS
        $keys = array_keys($vars);
        $keys = array_map(function($item){

            return '{{'.$item.'}}';

        }, $keys);


        //RETORNA O CONTEUDO RENDERIZADO
        return str_replace($keys, array_values($vars), $contentView);

     }

}