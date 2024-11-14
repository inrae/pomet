<?php

namespace App\Models;

use Ppci\Models\PpciModel;

class Individu extends PpciModel
{


    private $sql = "select ind_id, fk_ech_id,
			longueur::float, poids::float
			from individu";

    public function __construct()
    {
        $this->table = "individu";
        $this->fields = array(
            "ind_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "fk_ech_id" => array(
                "type" => 1,
                "requis" => 1,
                "parentAttrib" => 1
            ),
            "longueur" => array(
                "type" => 1
            ),
            "poids" => array(
                "type" => 1
            )
        );
        parent::__construct();
    }

    /**
     * Surcharge de la fonction lire pour reformater les champs en affichage
     * (non-PHPdoc)
     *
     * @see ObjetBDD::lire()
     */
    function read($id, $getDefault = false, $parentValue = 0): array
    {
        if ($id > 0) {
            return $this->getDetail($id);
        } else {
            return parent::lire($id, $getDefault, $parentValue);
        }
    }

    /**
     * Retourne le detail d'un individu
     *
     * @param int $id
     * @return array
     */
    function getDetail(int $id)
    {
        if ($id > 0) {
            $where = " where ind_id = :id:";
            return $this->lireParam($this->sql . $where, ["id" => $id]);
        }
    }

    /**
     * Retourne la liste des individus rattaches a un echantillon
     *
     * @param unknown $id
     * @return tableau
     */
    function getListFromEchantillon(int $id)
    {
        if ($id > 0 && is_numeric($id)) {
            $where = " where fk_ech_id = :id:";
            $order = " order by ind_id";
            return $this->getListeParam($this->sql . $where . $order,  ["id" => $id]);
        }
    }

    /**
     * Retourne le nombre de poissons, et leur masse totale,
     * pour un echantillon
     *
     * @param int $id
     *            : code de l'echantillon
     * @return array["nombre", "masse"]
     */
    function getTotalFromEchantillon(int $id)
    {
        if ($id > 0) {
            $sql = "select count(*) as nombre, sum(poids) as masse
					from individu
					where fk_ech_id = :id:";
            return $this->lireParam($sql,  ["id" => $id]);
        }
    }

    /**
     * Retourne la liste des individus correspondant a la liste des traits determinee
     * par les parametres de recherche fournis
     *
     * @param array $dataSearch
     * @return tableau
     */
    function getListForExport(array $dataSearch)
    {
        $trait = new TraitTable;
        $traits = $trait->getIdFromSearch($dataSearch);
        if (count($traits) > 0) {
            $sql = "select fk_trait_id as trait_id, fk_ech_id as ech_id, ind_id, nom, code_sandre, longueur, poids
					 from individu
					join echantillon on (fk_ech_id = ech_id)
					join espece using (espece_id)";
            $where = " where fk_trait_id in (";
            $virgule = "";
            $i = 0;
            $param = [];
            foreach ($traits as $value) {
                $where .= $virgule . ":id$i:";
                $param["id$i"] = $value["trait_id"];
                $virgule = ",";
                $i++;
            }
            $where .= ")";
            $order = " order by fk_trait_id";
            return $this->getListeParam($sql . $where . $order, $param);
        }
    }
}
