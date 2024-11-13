<?php

namespace App\Models;

use Ppci\Models\PpciModel;

class MasseEau extends PpciModel
{


    public function __construct()
    {
        $this->table = "masse_eau";
        $this->fields = array(
            "masse_eau_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "masse_eau" => array(
                "requis" => 1
            ),
            "code_agence" => array(
                "type" => 0
            ),
            "code_masse_eau" => array(
                "type" => 0
            ),
            "categorie" => array(
                "type" => 0
            ),
            "systeme" => array(
                "type" => 0
            ),
            "code_type" => array(
                "type" => 0
            ),
            "type_base" => array(
                "type" => 0
            ),
            "nom" => array(
                "type" => 0
            ),
            "code_f" => array(
                "type" => 0
            ),
            "ecoregion" => array(
                "type" => 0
            ),
            "agence" => array(
                "type" => 0
            ),
            "superficie" => array(
                "type" => 1
            ),
            "connectivite" => array(
                "type" => 0
            )
        );
        parent::__construct();
    }

    /**
     * Retourne la liste des masses d'eau
     *
     * @return array
     */
    function getMasses()
    {
        $sql = "select masse_eau_id, masse_eau from masse_eau order by masse_eau";
        return $this->getListeParam($sql);
    }
}
