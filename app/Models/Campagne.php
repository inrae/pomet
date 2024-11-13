<?php

namespace App\Models;

use Ppci\Models\PpciModel;

class Campagne extends PpciModel
{


    public function __construct()
    {
        $this->table = "campagnes";
        $this->fields = array(
            "campagne_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "fk_masse_eau" => array(
                "type" => 1,
                "requis" => 1,
                "parentAttrib" => 1
            ),
            "campagne_nom" => array(
                "requis" => 1,
                "longueur" => "100"
            ),
            "saison" => array(
                "requis" => 1
            ),
            "annee" => array(
                "type" => 1,
                "requis" => 1
            ),
            "fk_personne" => array(
                "type" => 1
            ),
            "experimentation_id" => array(
                "type" => 1
            ),
            "is_actif" => array(
                "type" => 1,
                "requis" => 1,
                "defaultValue" => 1
            )
        );
        parent::__construct();
    }

    /**
     * Ajoute le nombre de traits associes a une campagne
     * {@inheritDoc}
     * @see ObjetBDD::lire()
     */
    function read(int $id, bool $getDefault = false, $parentValue = 0): array
    {
        $data = parent::read($id, $getDefault, $parentValue);
        $data["traits"] = 0;
        if ($id > 0 && is_numeric($id)) {
            $data["traits"] = $this->getNbTraits($id);
        }
        return $data;
    }

    /**
     * Lit un enregistrement de campagnes avec la masse d'eau associee
     * @param int $id
     * @return array
     */
    function readWithMasseEau($id)
    {
        $sql = "select * from campagnes join masse_eau on (masse_eau_id = fk_masse_eau)
                where campagne_id = :id:";
        $param = ["id" => $id];
        return $this->lireParamAsPrepared($sql, $param);
    }

    function getNbTraits($id)
    {
        $sql = "select count(*) as traits from trait where fk_campagne_id = :id:";
        $param = ["id" => $id];
        $dataCount = $this->lireParam($sql, $param);
        return ($dataCount["traits"]);
    }

    function getListFromParam(array $param)
    {
        $sql = "select campagnes.*,
                 masse_eau, personne_id, personne.nom, prenom, experimentation_libelle
				 from campagne
				left outer join masse_eau on (masse_eau_id = fk_masse_eau)
				left outer join personne on (personne_id = fk_personne)
				left outer join experimentation using (experimentation_id)
                ";
        $order = " order by annee, saison, campagne_nom";

        $and = false;
        $where = " where ";
        $fields = array(
            "saison",
            "annee",
            "masse_eau_id"
        );
        $i = 0;
        $sqlparam = [];
        foreach ($fields as $field) {
            if (strlen($param[$field]) > 0) {
                $and ? $where .= " and " : $and = true;
                $where .= $field . " = :f" . $i . ":";
                $sqlparam["f" . $i] = $param[$field];
                $i++;
            }
        }
        if (!$and) {
            $where = "";
        }

        $data = $this->getListeParam($sql . $where . $order, $sqlparam);
        /*
         * Ajout du nombre de traits
         */
        foreach ($data as $key => $value) {
            $data[$key]["traits"] = $this->getNbTraits($value["campagne_id"]);
        }
        return $data;
    }

    function search($masse_eau_id, $annee, $saison)
    {
        $sql = "select campagne_id, fk_masse_eau, masse_eau, saison, annee, campagne_nom
                from campagnes
                join masse_eau on (masse_eau_id = fk_masse_eau)
                where fk_masse_eau = :masse_eau_id:
                and annee = :annee:
                and saison = :saison:
                 ";
        return $this->lireParamAsPrepared($sql, array("masse_eau_id" => $masse_eau_id, "annee" => $annee, "saison" => $saison));
    }

    /**
     * Fonction technique, retournant un tableau contenant les annees depuis l'annee
     * courante jusqu'en 2005
     *
     * @return array
     */
    function getAnnees()
    {
        $annees = array();
        for ($annee = date('Y') + 1; $annee >= 2005; $annee--) {
            $annees[] = $annee;
        }
        return $annees;
    }

    /**
     * Fonction technique, retournant les saisons
     *
     * @return array
     */
    function getSaisons()
    {
        return array(
            "printemps",
            "automne",
            "ete",
            "hiver"
        );
    }


    /**
     * Retourne la liste des campagnes autorisees pour un login
     *
     * @param string $login
     * @return tableau
     */
    function getListFromUser($login, $a_is_checked = array(), $param = array(), $is_actif = 1)
    {
        if (strlen($login) > 0) {
            $sql = "select campagne_id, campagne_nom, saison, annee, masse_eau,
					experimentation_id, experimentation_libelle";
            $where = " where ";
            $and = "";
            $sqlparam = [];
            if ($param["masse_eau_id"] > 0) {
                $where .= $and . "masse_eau_id = :masse_eau_id:";
                $sqlparam["masse_eau_id"] = $param["masse_eau_id"];
                $and = " and ";
            }
            if ($param["annee"] > 0) {
                $where .= $and . "annee = :annee:";
                $sqlparam["annee"] = $param["annee"];
                $and = " and ";
            }
            if (strlen($param["saison"]) > 0) {
                $where .= $and . "saison = :saison:";
                $sqlparam["saison"] = $param["saison"];
                $and = " and ";
            }
            /*
             * Teste si l'utilisateur dispose de tous les droits sur les campagnes
             */
            if ($_SESSION["rights"]["param"] == 1) {
                $from = " from campagnes
						join masse_eau on (masse_eau_id = fk_masse_eau)
					    left outer join experimentation using (experimentation_id)";
                if ($is_actif == 1) {
                    $where .= $and . "is_actif = 1";
                    $and = " and ";
                }
                $order = " order by annee desc, saison, masse_eau";
            } else {
                $from = " from campagnes
					 join webacl.acllogin_campagnes using (campagne_id)
					 join webacl.acllogin using (acllogin_id)
					join masse_eau on (masse_eau_id = fk_masse_eau)
					left outer join experimentation using (experimentation_id)";
                $where .= $and . "login = :login:";
                $sqlparam["login"] = $login;
                $order = " order by annee, saison, masse_eau";
            }
            $data = $this->getListeParam($sql . $from . $where . $order, $sqlparam);
            if (!empty($a_is_checked)) {
                foreach ($data as $key => $value) {
                    if (in_array($value["campagne_id"], $a_is_checked)) {
                        $data[$key]["is_checked"] = 1;
                    }
                }
            }
            return $data;
        }
    }

    /**
     * Retourne la liste des campagnes correspondant au tableau de cles fourni
     *
     * @param array $cles
     * @return tableau
     */
    function getListFromArray($cles)
    {
        $sql = "select campagne_id, campagne_nom, saison, annee, masse_eau,
				experimentation_libelle, experimentation_id, longitude, latitude, zoom,
                code_agence
				from campagnes
				join masse_eau on (masse_eau_id = fk_masse_eau)
				left outer join experimentation using (experimentation_id)
				";
        $where = " where campagne_id in (";
        $order = " order by campagne_nom";
        $comma = "";
        $i = 0;
        $param = [];
        foreach ($cles as $cle) {
            if ($cle > 0) {
                $where .= $comma . "k$i";
                $param["k$i"]  = $cle;
                $comma = ",";
                $i++;
            }
        }
        $where .= ")";
        return $this->getListeParam($sql . $where . $order, $param);
    }
}


