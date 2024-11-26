<?php

namespace App\Models;

use Ppci\Models\PpciModel;

class Personne extends PpciModel
{


    public function __construct()
    {
        $this->table = "personne";
        $this->fields = array(
            "personne_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "nom" => array(
                "type" => 0,
                "requis" => 1
            ),
            "prenom" => array(
                "type" => 0,
                "requis" => 1
            ),
            "institut" => array(
                "type" => 0,
                "requis" => 1
            ),
            "telephone" => array(
                "type" => 0
            ),
            "email" => array(
                "type" => 0
            ),
            "adresse" => array(
                "type" => 0
            )
        );
        parent::__construct();
    }

    /**
     * Retourne la liste des valeurs uniques du champ fourni
     *
     * @param string $champ
     * @return tableau
     */
    function getUniqueValues($champ)
    {
        if (strlen($champ) > 0 && array_key_exists($champ, $this->fields)) {
            $sql = "select distinct " . $champ . " as field from personne order by " . $champ;
            return $this->getListeParam($sql);
        }
    }

    /**
     * Rajout de nombre de fils pour eviter de supprimer un pere par erreur
     *
     * @see ObjetBDD::lire()
     */
    function read($id, $getDefault = false, $parentValue = 0): array
    {
        $data = parent::read($id, $getDefault, $parentValue);
        if ($id > 0) {
            $sql = "select count(*) as children from campagnes where fk_personne = :id: ";
            $dataCount = $this->lireParam($sql, ["id" => $id]);
            $data["children"] = $dataCount["children"];
        }
        return $data;
    }
}
