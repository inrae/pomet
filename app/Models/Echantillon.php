<?php

namespace App\Models;

use Ppci\Libraries\PpciException;
use Ppci\Models\PpciModel;

class Echantillon extends PpciModel
{


    private $sql = "select ech_id, espece_id, fk_trait_id,
			nt::int, pt::float, nom, nom_fr, lt_max
			from echantillon
			natural join espece
			left outer join species_size on (nom = species_consensus)";

    public function __construct()
    {
        $this->table = "echantillon";
        $this->fields = array(
            "ech_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "espece_id" => array(
                "type" => 1,
                "requis" => 1
            ),
            "fk_trait_id" => array(
                "type" => 1,
                "requis" => 1,
                "parentAttrib" => 1
            ),
            "nt" => array(
                "type" => 1
            ),
            "pt" => array(
                "type" => 1
            )
        );
        parent::__construct();
    }

    /**
     * Retourne le nombre d'echantillons pour une espece donnee
     *
     * @param int $espece_id
     * @return int
     */
    function getNbFromEspece(int $espece_id)
    {
        if ($espece_id > 0) {
            $sql = "select count(*) as nombre from echantillon where espece_id = :id:";
            $data = $this->lireParam($sql, ["id" => $espece_id]);
            return ($data["nombre"]);
        }
    }

    /**
     * Retourne la liste des echantillons pour un trait
     *
     * @param int $trait_id
     * @return tableau
     */
    function getListeFromTrait(int $trait_id)
    {
        if ($trait_id > 0) {
            $where = " where fk_trait_id = :id:";
            $order = " order by ech_id ";
            return $this->getListeParam($this->sql . $where . $order, ["id" => $trait_id]);
        }
    }

    function read($id, $getDefault = false, $parentValue = 0): array
    {
        if ($id > 0) {
            return $this->getDetail($id);
        } else {
            return parent::read($id, $getDefault, $parentValue);
        }
    }

    /**
     * Recalcule le nombre total et la masse totale pour l'échantillon, et met a jour
     * l'information le cas echeant
     *
     * @param int $id
     */
    function updateNbTotal($id)
    {
        if ($id > 0 && is_numeric($id)) {
            $individu = new Individu;
            $data = $this->lire($id);
            $updateRequired = false;
            $dataIndiv = $individu->getTotalFromEchantillon($id);
            if ($dataIndiv["nombre"] > $data["nt"]) {
                $data["nt"] = $dataIndiv["nombre"];
                $updateRequired = true;
            }
            if ($dataIndiv["masse"] > $data["pt"]) {
                $data["pt"] = $dataIndiv["masse"];
                $updateRequired = true;
            }
            if ($updateRequired) {
                parent::write($data);
            }
        }
    }

    /**
     * Surcharge de la fonction supprimer pour enlever les poissons rattaches
     *
     * @see ObjetBDD::supprimer()
     */
    function supprimer($id)
    {
        if ($id > 0) {
            /*
             * Suppression des individus rattaches
             */
            $individu = new Individu;
            $individu->supprimerChamp($id, "fk_ech_id");
            return parent::supprimer($id);
        }
    }

    /**
     * Retourne le nombre d'echantillons rattaches a un trait
     *
     * @param int $traitId
     * @return int
     */
    function getNombreFromTrait(int $traitId)
    {
        if ($traitId > 0) {
            $sql = "select count(*) as nombre from echantillon
					where fk_trait_id = :id:" . $traitId;
            $data = $this->readParam($sql, ["id" => $traitId]);
            return ($data["nombre"]);
        }
    }

    /**
     * Retourne le detail d'un echantillon
     *
     * @param int $id
     * @return array
     */
    function getDetail($id)
    {
        if ($id > 0) {
            $where = " where ech_id = :id:";
            return $this->readParam($this->sql . $where, ["id" => $id]);
        } else {
            return [];
        }
    }

    /**
     * Prepare la liste des echantillons a exporter, qui correspondent aux parametres de recherche
     * des traits fournis dans le tableau
     *
     * @param array $dataSearch
     * @return array
     */
    function getListForExport(array $dataSearch)
    {
        $trait = new TraitTable;
        $traits = $trait->getIdFromSearch($dataSearch);
        if (count($traits) > 0) {
            $sql = "select fk_trait_id as trait_id, ech_id, nom, code_sandre, nt, pt
					from echantillon
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
