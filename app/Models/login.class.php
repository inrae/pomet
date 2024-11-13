<?php 
namespace App\Models;
use Ppci\Models\PpciModel;
require_once 'framework/droits/acllogin.class.php';

/**
 * Classe permettant de manipuler la table acllogin_campagne et d'interroger la table des logins
 * @author quinton
 *
 */
class Login
{
	public $aclLogin;

	public function __construct()
	{
		$this->aclLogin = new Acllogin($bdd_gacl, $param);
	}

	/**
	 * Retourne la liste des logins, avec 1 ou 0 selon qu'ils sont associes ou non
	 * a la campagne
	 * @param int $campagne_id
	 * @return tableau
	 */
	function getLoginsFromCampagne($campagne_id)
	{
		if (is_numeric($campagne_id)) {
			$sql = "select acllogin_id, login, logindetail, '1' as is_selected
					from acllogin
					join acllogin_campagnes using (acllogin_id)
					where campagne_id = " . $campagne_id . "
					union
					select acllogin_id, login, logindetail, '0' as is_selected
					from acllogin
					where acllogin_id not in
					(select acllogin_id from acllogin_campagnes where campagne_id = " . $campagne_id . ")";
			$data = $this->aclLogin->getListeParam($sql);
			foreach ($data as $key => $row) {
				$logindetail[$key]  = $row['logindetail'];
			}
			array_multisort($logindetail, SORT_REGULAR, $data);
			return $data;
		}
	}

	function getOnlyLoginsFromCampagne($campagne_id) {
		$sql = "select acllogin_id from acllogin_campagnes where campagne_id = :campagne_id";
		$logins = $this->aclLogin->getListeParamAsPrepared($sql, array("campagne_id"=>$campagne_id));
		$ret = array();
		foreach ($logins as $login) {
			$ret[] = $login["acllogin_id"];
		}
		return $ret;
	}

	/**
	 * Ecrit la liste des logins rattaches a la campagne
	 * @param int $campagne_id
	 * @param array $logins
	 */
	function setLogins($campagne_id, $logins)
	{
		/*
		 * Verification des logins fournis
		 */
		$controle = true;
		foreach ($logins as $value) {
			if (!is_numeric($value)) {
				$controle = false;
			}
		}
		if ($campagne_id > 0 && is_numeric($campagne_id) && $controle) {
			$this->aclLogin->ecrireTableNN("acllogin_campagnes", "campagne_id", "acllogin_id", $campagne_id, $logins);
		}
	}
	/**
	 * Supprime le rattachement de logins a une campagne
	 * @param int $campagne_id
	 * @return code
	 */
	function purgeLoginFromCampagne($campagne_id)
	{
		if (is_numeric($campagne_id) && $campagne_id > 0) {
			$sql = "delete from acllogin_campagnes where campagne_id = " . $campagne_id;
			return $this->aclLogin->executeSQL($sql);
		}
	}
}
