<?php
//Classe responsável por gerencia o cache em arquivo

namespace App\Utils\Cache;

class File{

    /**
     * Método responsavel por retornar o caminho até o arquivo de cache
     * @param   string $hash
     * @return  string 
    */
    private static function getFilePath($hash){
        //DIRETORIO DE CACHE
        $dir = getenv('CACHE_DIR');

        //VERIFICA A EXISTENCIA DO DIRETORIO
        if(!file_exists($dir)){
            mkdir($dir,0755, true);
        }

        //RETORNA O CAMINHO ATÉ O ARQUIVO
        return $dir.'/'.$hash;
    }

    /**
     * Método responsavel por guardar informações no cache
     * @param string $hash
     * @param mixed  $content
     * @return boolean
    */
    public static function storageCache($hash, $content){
        //SERIALIZA O RETORNO
        $serialize = serialize($content);

        //OBTEM O CAMINHA ATÉ O CAMINHO DE CACHE
        $cacheFile = self::getFilePath($hash);

        //GRAVA AS INFORMAÇÕES NO ARQUIVO
        return file_put_contents($cacheFile,$serialize);

    }

    /**
     * Método responsavel por retornar o conteudo gravado no cache
     * @param string    $hash
     * @param integer   $expiration
     * @return mixed
    */
    private static function getContentCache($hash, $expiration){
        //OBTEM O CAMINHO DO ARQUIVO
        $cacheFile = self::getFilePath($hash);

        //VERIFICA SE O ARQUIVO EXISTE
        if(!file_exists($cacheFile)){
            return false;
        }

        //VALIDA A EXPIRAÇÃO DO CACHE
        $createTime = filectime($cacheFile);
        $diffTime = time() - $createTime;
        if($diffTime > $expiration){    
            return false;
        }

        //RETORNA O DADO REAL 'DESERIALIZADO'
        $serialize = file_get_contents($cacheFile);
        return unserialize($serialize);

    }

    /**
     * Método responsavel por obter uma informação no cache
     * @param   string $hash
     * @param   integer $expiration
     * @return  mixed $function
    */
    public static function getCache($hash, $expiration, $function){
        //VERIFICA O CONTEUDO GRAVADO
        if($content = self::getContentCache($hash, $expiration)){
            return $content;
        }

        //EXECUÇÃO DA FUNÇÃO
        $content = $function();

        //GRAVA O CONTEUDO NO CACHE
        self::storageCache($hash, $content);

        //RETURNA O CONTEUDO
        return $content;

    }
}
