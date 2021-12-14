<?php 

namespace App\Controller\Pages;

use \App\Utils\View;

class Page{

    /**
     * Método responsável por retornar o topo da página renderizado
     * @param  
     * @param 
     * @return string
     */
    public static function getHeader(){
        return View::render('pages/header');
    }

    /**
     * Método responsavel por retornar um link da paginação
     * @param array $queryParams
     * @param array $page
     * @param string $url
     * @param string $label texto do ultimo link da paginação
     * @return string
    */
    private static function getPaginationLink($queryParams, $page, $url,$label=null){
        //ALTERA A PÁGINA
        $queryParams['page'] = $page['page'];

        
        //LINK DA PAGINA
        $link = $url.'?'.http_build_query($queryParams);

        //VIEW
        return View::render('pages/pagination/link', [
            'page'   =>$label ?? $page['page'],
            'link'   => $link,
            'active' => $page['current'] ? 'active' : ''
        ]);
    }


    /**
     * Método responsável por renderizar o layout de paginação
     * @param  Request $request
     * @param  pagination $obPagination
     * @return string
     */
    public static function getPagination($request, $obPagination){
        //OBTER AS PÁGINAS
        $pages = $obPagination->getPages();
        
        //VERIFICA A QUANTIDADE DE PAGES
        if(count($pages) <= 1) return '';
        
        //LINKS
        $links = '';
        
        //URL ATUAL (SEM GETS)
        $url = $request->getRouter()->getCurrentUrl();
        
        //GET
        $queryParams = $request->getQueryParams();

        //PAGINA ATUAL
        $currentPage = $queryParams['page'] ?? 1;

        //LIMITE DE PAGINAS
        $limit = getenv('PAGINATION_LIMIT');

        //MEIO DA PÁGINAÇÃO
        $middle = ceil($limit/2);

        //INICIO DA PAGINAÇAO
        $start = $middle > $currentPage ? 0 : $currentPage - $middle;

        //AJUSTA O FINAL DA PAGINAÇÃO
        $limit = $limit + $start;

        //AJUSTA O INICIO DA PÁGINAÇÃO
        if($limit > count($pages)){
            $diff = $limit - count($pages);
            $start = $start - $diff;
        }

        //LINK INICIAL
        if($start > 0){
            $links .= self::getPaginationLink($queryParams,reset($pages), $url ,'<<');
        }

                
        //RENDERIZA OS LINKS
        foreach ($pages as $page) {
            //VERIFICA O START DA PAGIAÇÃO
            if($page['page'] <= $start) continue;

            //VERIFICA O LIMITE DA PAGINAÇÃO
            if($page['page'] > $limit){
                $links .= self::getPaginationLink($queryParams,end($pages), $url ,'>>');
                break;
            }

            $links .= self::getPaginationLink($queryParams, $page, $url);
            
        }

        //RENDERIZA BOX DE PAGINAÇÃO
        return View::render('pages/pagination/box', [
            'links'   => $links
        ]);
        


    }

    /**
     * Método responsável por retornar o rodapé da página renderizado
     * @param 
     * @param 
     * @return string
     */
    public static function getFooter(){
        return View::render('pages/footer',[
            'copy'=>'&copy;AglaDev - 2021 Todos os direitos reservados!'
        ]);
    }


    /**
     * Método responsável por retornar o conteudo (view) da nossa página géneria
     * @param $title   titulo da página
     * @param $content conteudo da página
     * @return string
     */

     public static function getPage($title, $content){

        return View::render('pages/page', [
            'title'   =>$title,
            'header'  =>self::getHeader(),
            'content' =>$content,
            'footer'  =>self::getFooter()
        ]);

     }
}