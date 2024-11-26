<?php

namespace App\Libraries;

/**
 * Classe de recherche des traits
 *
 * @author quinton
 *
 */
class SearchTrait extends SearchParam
{

    public function __construct()
    {
        $this->param = array(
            "campagne_id" => "",
            "saison" => "",
            "annee" => "",
            "masse_eau_id" => ""
        );
        $this->paramNum = array(
            "campagne_id",
            "annee",
            "masse_eau_id"
        );
        $this->paramArray = array(
            "campagne_id" => "1"
        );
        parent::__construct();
    }

    /**
     * Retourne si au moins une campagne est selectionnee
     *
     * @return boolean
     */
    function hasCampagneSelected()
    {
        $hasSelected = false;
        foreach ($this->param["campagne_id"] as $value) {
            if ($value > 0) {
                $hasSelected = true;
                break;
            }
        }
        return $hasSelected;
    }
}
