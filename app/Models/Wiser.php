<?php

namespace App\Models;

use Ppci\Models\PpciModel;

/**
 * ORM de gestion de la table guilde_wiser
 *
 * @author quinton
 *
 */
class Wiser extends PpciModel
{

    public function __construct()
    {
        $this->table = "guilde_wiser";
        $this->useAutoIncrement = false;
        $this->fields = array(
            "espece_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "name" => array(
                "type" => 0,
                "requis" => 1
            ),
            "Ecological_guild" => array(
                "type" => 0
            ),
            "Position_guild" => array(
                "type" => 0
            ),
            "Trophic_guild" => array(
                "type" => 0
            ),
            "trophic_index_fishbase" => array(
                "type" => 1
            )
        );
        parent::__construct();
    }
}
