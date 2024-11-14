<?php

namespace App\Models;

use Ppci\Models\PpciModel;

class DCEpointGeom extends PpciModel
{

    public function __construct()
    {
        $this->table = "dce_point_geom";
        $this->useAutoIncrement = false;
        $this->fields = array(
            "trait_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1
            ),
            "pos_deb_long_dd" => array(
                "type" => 1,
                "requis" => 1
            ),
            "pos_deb_lat_dd" => array(
                "type" => 1,
                "requis" => 1
            ),
            "trait_geom" => array(
                "type" => 4
            )
        );
        $param["srid"] = 4326;
        parent::__construct();
    }

    /**
     * Surcharge de la fonction ecrire pour generer le point geographique
     * (non-PHPdoc)
     *
     * @see ObjetBDD::ecrire()
     */
    function write($data): int
    {
        /*
         * Preparation de trait_geom
         */
        /*
         * Preparation de trait_geom
         */
        $id = parent::write($data);
        if ($id > 0) {
            $sql = "update dce_ligne_geom set trait_geom = st_setsrid(st_makepoint(:pos_deb_long_dd:, :pos_deb_lat_dd:),4326)
            where trait_id = :id:";
        }
        $param = [
            "id" => $id,
            "pos_deb_long_dd" => $data["pos_deb_long_dd"],
            "pos_deb_lat_dd" => $data["pos_deb_lat_dd"],
        ];
        $this->executeSQL($sql, $param, true);
        return $id;
    }
}
