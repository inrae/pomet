<?php

namespace App\Models;

use Ppci\Models\PpciModel;

/**
 * ORM de gestion de la table resilience
 * @author quinton
 *
 */
class Resilience extends PpciModel
{
    /**
     * Constructeur
     *
     * @param PDO $bdd
     * @param array $param
     */
    public function __construct()
    {
        $this->table = "resilience";
        $this->useAutoIncrement = false;
        $this->fields = array(
            "resilience_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "resilience_name" => array(
                "type" => 0,
                "requis" => 1
            )
        );
        parent::__construct();
    }
}
