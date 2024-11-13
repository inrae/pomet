<?php

namespace App\Models;

use Ppci\Models\PpciModel;

/**
 * ORM de gestion de la table guilde
 *
 * @author quinton
 *
 */
class Guilde extends PpciModel
{

    public function __construct()
    {
        $this->table = "guilde";
        $this->useAutoIncrement = false;
        $this->fields = array(
            "espece_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "nom" => array(
                "type" => 0,
                "requis" => 1
            ),
            "guilde_ecologique_dce2007" => array(
                "type" => 0
            ),
            "guilde_trophique_dce2007" => array(
                "type" => 0
            ),
            "guilde_trophique_lp" => array(
                "type" => 0
            ),
            "index_trophique_fb2006" => array(
                "type" => 1
            ),
            "repartition_dce2007" => array(
                "type" => 0
            )
        );
        parent::__construct();
    }
}
