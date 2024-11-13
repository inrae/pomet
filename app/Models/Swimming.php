<?php

namespace App\Models;

use Ppci\Models\PpciModel;

/**
 * ORM de gestion de la table swimming
 * @author quinton
 *
 */
class Swimming extends PpciModel
{

    public function __construct()
    {
        $this->table = "swimming";
        $this->useAutoIncrement = false;
        $this->fields = array(
            "swimming_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "swimming_name" => array(
                "type" => 0,
                "requis" => 1
            )
        );
        parent::__construct();
    }
}
