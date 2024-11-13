<?php

namespace App\Models;

use Ppci\Models\PpciModel;

/**
 * ORM de gestion de la table species_size
 * @author quinton
 *
 */
class Size extends PpciModel
{

    public function __construct()
    {
        $this->table = "species_size";
        $this->useAutoIncrement = false;
        $this->fields = array(
            "species_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "species_consensus" => array(
                "type" => 0,
                "requis" => 1
            ),
            "lt_maturity" => array(
                "type" => 1
            ),
            "age_maturity" => array(
                "type" => 1
            ),
            "lt_max" => array(
                "type" => 1
            ),
            "age_max" => array(
                "type" => 1
            ),
            "lt_maturity" => array(
                "type" => 1
            ),
            "iucn" => array(
                "type" => 0
            ),
            "spawn_frequence" => array(
                "type" => 1
            ),
            "repro_guild" => array(
                "type" => 1
            ),
            "parent_care" => array(
                "type" => 1
            ),
            "fecondity_max" => array(
                "type" => 1
            ),
            "egg_size" => array(
                "type" => 1
            ),
            "swimming_id" => array(
                "type" => 1
            ),
            "resilience_id" => array(
                "type" => 1
            )
        );
        parent::__construct();
    }
    /**
     * Retourne l'enregistrement pour l'espèce considérée
     * @param string $name
     */
    function getFromName($name)
    {
        if (strlen($name) > 0) {
            $sql = "select * from species_size
			 left outer join swimming using (swimming_id)
			  left outer join resilience using (resilience_id)
			  where species_consensus = :name:";
            return $this->lireParamAsPrepared($sql, array("name" => $name));
        } else {
            return [];
        }
    }
}
