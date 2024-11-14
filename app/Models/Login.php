<?php

namespace App\Models;

use Ppci\Models\Acllogin;

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
		$this->aclLogin = new Acllogin;
	}

	/**
	 * Retourne la liste des logins, avec 1 ou 0 selon qu'ils sont associes ou non
	 * a la campagne
	 * @param int $campagne_id
	 * @return array
	 */
	function getLoginsFromCampagne(int $campagne_id)
	{

		$sql = "select acllogin_id, login, logindetail, '1' as is_selected
					from acllogin
					join acllogin_campagnes using (acllogin_id)
					where campagne_id = :id:
					union
					select acllogin_id, login, logindetail, '0' as is_selected
					from acllogin
					where acllogin_id not in
					(select acllogin_id from acllogin_campagnes where campagne_id = :id2:)";
		$data = $this->aclLogin->getListeParam($sql, ["id" => $campagne_id, "id2" => $campagne_id]);
		foreach ($data as $key => $row) {
			$logindetail[$key]  = $row['logindetail'];
		}
		array_multisort($logindetail, SORT_REGULAR, $data);
		return $data;
	}

	function getOnlyLoginsFromCampagne($campagne_id)
	{
		$sql = "select acllogin_id from acllogin_campagnes where campagne_id = :campagne_id:";
		$logins = $this->aclLogin->getListeParamAsPrepared($sql, array("campagne_id" => $campagne_id));
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
	function setLogins(int $campagne_id, $logins)
	{
		$this->aclLogin->ecrireTableNN("acllogin_campagnes", "campagne_id", "acllogin_id", $campagne_id, $logins);
	}

	function purgeLoginFromCampagne(int $campagne_id)
	{
		$sql = "delete from acllogin_campagnes where campagne_id = :id:";
		$this->aclLogin->executeSQL($sql, ["id" => $campagne_id], true);
	}
}
