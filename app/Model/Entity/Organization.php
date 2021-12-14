<?php

namespace App\Model\Entity;

//Classe que representa a tabela da empresa-objeto-entidade
class Organization{

    /**
     * Id da organização
     * @var  integer
     **/
    public $id = 1;

    /**
     * nome da organização
     * @var  string
     **/
    public $name =  'AglaDev';

    /**
     * site da organização
     * @var  string
     **/
    public $site = 'https//:agladev.com';
    
    /**
     * descrição da organização
     * @var  string
     **/
    public $description = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia, corrupti!';
}