<?php

namespace App\Libraries;

/**
 * Classe de recherche des campagnes
 *
 * @author quinton
 *
 */
class SearchCampagne extends SearchParam
{

    public function __construct()
    {
        $this->param = array(
            "saison" => "",
            "annee" => "",
            "masse_eau_id" => ""
        );
        $this->paramNum = array(
            "annee",
            "masse_eau_id"
        );
        parent::__construct();
    }
}
