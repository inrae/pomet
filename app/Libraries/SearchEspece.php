<?php

namespace App\Libraries;

/**
 * Classe de recherche des especes
 *
 * @author Eric Quinton
 *
 */
class SearchEspece extends SearchParam
{

    public function __construct()
    {
        $this->param = array(
            "nom" => "",
            "phylum" => "",
            "subphylum" => "",
            "classe" => "",
            "ordre" => "",
            "famille" => "",
            "genre" => ""
        );
        parent::__construct();
    }
}
