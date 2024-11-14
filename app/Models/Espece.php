<?php

namespace App\Models;

use Ppci\Libraries\PpciException;
use Ppci\Models\PpciModel;

/**
 * ORM de gestion de la table espece
 *
 * @author quinton
 *
 */
class Espece extends PpciModel
{

	private $sqlparam = [];
	private $where = "";
	/**
	 * Constructeur
	 *
	 * @param PDO $bdd
	 * @param array $param
	 */
	public function __construct()
	{
		$this->table = "espece";
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
			"nom_fr" => array(
				"type" => 0
			),
			"auteur" => array(
				"type" => 0
			),
			"phylum" => array(
				"type" => 0
			),
			"subphylum" => array(
				"type" => 0
			),
			"classe" => array(
				"type" => 0
			),
			"ordre" => array(
				"type" => 0
			),
			"famille" => array(
				"type" => 0
			),
			"genre" => array(
				"type" => 0
			),
			"code_perm_ifremer" => array(
				"type" => 1
			),
			"code_sandre" => array(
				"type" => 1
			)
		);
		parent::__construct();
	}

	/**
	 * Fonction retournant les differentes valeurs uniques d'un champ,
	 * pour retrouver, par exemple, la liste des classes, ordres, etc.
	 *
	 * @param string $champ
	 * @return tableau
	 */
	function getUniqueValues($champ)
	{
		if (strlen($champ) > 0 && array_key_exists($champ, $this->fields)) {
			$sql = "select distinct " . $champ . " as field from espece order by " . $champ;
			return $this->getListeParam($sql);
		}
	}
	/**
	 * Retourne la liste des valeurs d'un attribut rattaches a un autre attribut
	 * (subphylum attache a un phylum, par exemple)
	 *
	 * @param string $champ
	 *        	: colonne a rechercher
	 * @param string $champParent
	 *        	: colonne parente
	 * @param string $valeur
	 *        	: valeur parente
	 * @return tableau
	 */
	function getUniqueValuesFromParent($champ, $champParent, $valeur)
	{
		if (strlen($champ) > 0 && array_key_exists($champ, $this->fields) && strlen($champParent) > 0 && array_key_exists($champParent, $this->fields) && strlen($valeur) > 0) {
			$sql = "select distinct " . $champ . " as field from espece
							where " . $champParent . " = :val:
							and " . $champ . " is not null
							order by " . $champ;
			return $this->getListeParam($sql, ["val" => $valeur]);
		}
	}

	/**
	 * Fonction recherchant les especes en fonction des parametres fournis
	 *
	 * @param array $param
	 * @return array
	 */
	function getListFromParam(array $param)
	{
		$sql = "select * from espece
				 left outer join species_size on (nom = species_consensus)";
		$order = " order by nom";
		$this->getWhere($param);
		return $this->getListeParam($sql . $this->where . $order, $this->sqlparam);
	}
	/**
	 * Genere la clause where a partir des parametres de recherche
	 * @param array $param
	 * @return string
	 */
	function getWhere($param)
	{
		$and = false;
		$this->where = " where ";
		if (strlen($param["nom"]) > 0) {
			if (is_numeric($param["nom"])) {
				$this->where .= " code_sandre = :sandre:";
				$this->sqlparam["sandre"] = $param["nom"];
			} else {
				$this->where .= "(upper(nom) like upper(:nom:)
					or upper(nom_fr) like upper(:nom_fr:)";
				$this->sqlparam["nom"] = "%" . $param["nom"] . "%";
				$this->sqlparam["nom_fr"] = "%" . $param["nom"] . "%";
			}
			$and = true;
		}
		$fields = array(
			"phylum",
			"subphylum",
			"classe",
			"ordre",
			"famille",
			"genre"
		);
		foreach ($fields as $field) {
			if (strlen($param[$field]) > 0) {
				$and ? $this->where .= " and " : $and = true;
				$this->where .= " $field = :$field:";
				$this->sqlparam[$field] = $param[$field];
			}
		}
		if (!$and) {
			$this->where = "";
		}
	}

	/**
	 * Retourne la liste des especes en fonction des parametres fournis,
	 * associee a la table guilde_wiser
	 * @param unknown $param
	 * @return array
	 */
	function getListForExport($param)
	{
		$sql = "select * from espece left outer join guilde_wiser using (espece_id)";
		$order = " order by nom";
		$this->getWhere($param);
		return $this->getListeParam($sql . $this->where . $order, $this->sqlparam);
	}

	/**
	 * Supprime une espece de la table
	 */
	function supprimer($id)
	{
		if ($id > 0) {
			/*
			 * Recherche si l'espece est utilisee
			 */
			$echantillon = new Echantillon;
			if ($echantillon->getNbFromEspece($id) == 0) {
				$wiser = new Wiser;
				$wiser->supprimer($id);
				$guilde = new Guilde;
				$guilde->supprimer($id);
				return parent::supprimer($id);
			} else {
				$this->message->set("L'espèce est utilisée dans un trait, et ne peut être supprimée", true);
			}
		}
	}

	function read($id, $getDefault = false, $parentAttrib = 0): array
	{
		$data = parent::lire($id, $getDefault, $parentAttrib);
		if ($id > 0) {
			$echantillon = new Echantillon;
			$data["children"] = $echantillon->getNbFromEspece($id);
		}
		return $data;
	}
}
