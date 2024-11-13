<?php 
namespace App\Models;
use Ppci\Models\PpciModel;
/**
 * @author Eric Quinton
 * @copyright Copyright (c) 2015, IRSTEA / Eric Quinton
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 *  Creation 15 oct. 2015
 */

/**
 * ORM de gestion de la table materiel
 *
 * @author quinton
 *
 */
class Materiel extends PpciModel {
	/**
	 * Constructeur
	 *
	 * @param PDO $bdd
	 * @param array $param
	 */
	private $sql = "select materiel_id, materiel_type, materiel_nom, materiel_code, materiel_description,
			experimentation_libelle
			from materiel
			left outer join experimentation using (experimentation_id)";
	private $order = " order by materiel_nom";
	/**
	 * Constructeur
	 * @param PDO $bdd
	 * @param array $param
	 */
	public function __construct()
		$this->table = "materiel";
				$this->fields = array (
				"materiel_id" => array (
						"type" => 1,
						"key" => 1,
						"requis" => 1,
						"defaultValue" => 0
				),
				"materiel_type" => array (
						"type" => 0
				),
				"materiel_nom" => array (
						"type" => 0,
						"requis" => 1
				),
				"materiel_code" => array (
						"type" => 0
				),
				"materiel_description" => array (
						"type" => 0
				),
				"experimentation_id" => array (
						"type" => 1
				)
		);
		parent::__construct();	}

	/**
	 * Surcharge de la fonction getListe pour rajouter
	 * la jointure avec la table experimentation
	 * (non-PHPdoc)
	 * @see ObjetBDD::getListe()
	 */
	function getListe($order=0) {
		$sql = "select * from ".$this->table."
				left outer join experimentation using (experimentation_id)";
		$order > 0 ? $orderby = " order by ".$order : $orderby = "";
		return $this->getListeParam($sql.$orderby);
	}

	/**
	 * Retourne la liste des valeurs uniques du champ fourni
	 * @param unknown $champ
	 * @return tableau
	 */
	function getUniqueValues($champ) {
		$champ = $this->encodeData ( $champ );
		if (strlen ( $champ ) > 0 && array_key_exists ( $champ, $this->fields )) {
			$sql = "select distinct " . $champ . " as field from " . $this->table . " order by " . $champ;
			return $this->getListeParam ( $sql );
		}
	}

	/**
	 * Retourne la liste des materiels utilises dans le cadre de la DCE
	 * @return tableau
	 */
	function getListFromDce() {
		$sql = "select materiel_id, materiel_nom from materiel where experimentation_id = 1 order by materiel_nom";
		return $this->getListeParam($sql);
	}
	/**
	 * Retourne la liste des materiels associes a une experimentation
	 * @param string $exp
	 * @return array
	 */
	function getListFromExperimentation ($exp) {
		if (strlen($exp) > 0) {
			$exp = $this->encodeData($exp);
			$where = " where experimentation_libelle = '".$exp."'";
			return $this->getListeParam($this->sql.$where.$this->order);
		}
	}

	/**
	 * Prépare la liste des matériels pour l'export
	 * @return array
	 */
	function getListForExport() {
		return $this->getListeParam($this->sql.$this->order);
	}

}

?>
