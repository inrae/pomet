<?php

namespace App\Models;

use Ppci\Models\PpciModel;

class Salinite extends PpciModel
{

    public function __construct()
    {
        $this->table = "salinite";
        $this->fields = array(
            "salinite_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "salinite_libelle" => array(
                "type" => 0,
                "requis" => 1
            )
        );
        parent::__construct();
    }
}
