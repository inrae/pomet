<?php

namespace App\Libraries;

use App\Models\Campagne as ModelsCampagne;
use App\Models\Experimentation;
use App\Models\Login;
use App\Models\MasseEau;
use App\Models\Personne;
use Ppci\Libraries\PpciException;
use Ppci\Libraries\PpciLibrary;

class Campagne extends PpciLibrary
{
    function __construct()
    {
        parent::__construct();
        $this->dataclass = new ModelsCampagne;
        $this->keyName = "campagne_id";
        if (isset($_REQUEST[$this->keyName])) {
            $this->id = $_REQUEST[$this->keyName];
        }
    }

    function list()
    {
        $this->vue = service('Smarty');
        /**
		 * Display the list of all records of the table
		 */
        if (!isset($_SESSION["searchCampagne"])) {
            $_SESSION["searchCampagne"] = new SearchCampagne;
        }
        $_SESSION["searchCampagne"]->setParam($_REQUEST);
        $dataSearch = $_SESSION["searchCampagne"]->getParam();
        if ($_SESSION["searchCampagne"]->isSearch() == 1) {
            $this->vue->set($this->dataclass->getListFromParam($dataSearch), "data");
            $this->vue->set(1, "isSearch");
        }
        $this->campagneInitSelectList($this->vue);
        $this->vue->set($dataSearch, "dataSearch");
        $this->vue->set("campagne/campagneList.tpl", "corps");
        $masse_eau = new MasseEau;
        $this->vue->set($masse_eau->getListe(2), "masse_eau");
        return $this->vue->send();
    }
    function change()
    {
        $this->vue = service('Smarty');
        $this->dataRead($this->id, "campagne/campagneChange.tpl");
        $this->campagneInitSelectList($this->vue);
        $experimentation = new Experimentation;
        $this->vue->set($experimentation->getListe(2), "experimentation");
        $masse_eau = new MasseEau;
        $this->vue->set($masse_eau->getListe(2), "masse_eau");
        $personne = new Personne;
        $this->vue->set($personne->getListe(2), "personne");
        /**
         * Ajout de la selection des logins autorises
         */
        $login = new Login;
        $this->vue->set($login->getLoginsFromCampagne($this->id), "logins");
        return $this->vue->send();
    }
    function write()
    {
        try {
            $this->id = $this->dataWrite($_REQUEST);
            $_REQUEST[$this->keyName] = $this->id;
            if (!is_array($_REQUEST["acllogin_id"]) && strlen($_REQUEST["acllogin_id"]) > 0) {
                /**
                 * Nothing to do
                 */
            } else {
                /**
                 * Mise a jour des logins rattaches
                 */
                $login = new Login;
                $login->setLogins($this->id, $_REQUEST["acllogin_id"]);
            }
            return true;
        } catch (PpciException) {
            return false;
        }
    }
    function delete()
    {
        /**
		 * delete record
		 */
        /**
         * Suppression du rattachement des logins a la campagne
         */
        try {
            $login = new Login;
            $login->purgeLoginFromCampagne($this->id);
            $this->dataDelete($this->id);
            return true;
        } catch (PpciException) {
            $this->message->set("La suppression de la campagne a echoué");
            return false;
        }
    }
    function searchTrait()
    {
        /**
         * Recherche les campagnes pour les utilisateurs, avec les parametres de recherche adequats
         */
        translateIdInstanciate("ti_campagne");
        $this->vue->set(
            $_SESSION["ti_campagne"]->translateList(
                $this->dataclass->getListFromUser(
                    $_SESSION["login"],
                    array(),
                    array(
                        "masse_eau_id" => $_REQUEST["masse_eau_id"],
                        "annee" => $_REQUEST["annee"],
                        "saison" => $_REQUEST["saison"]
                    )
                )
            )
        );
    }
    function duplicate()
    {
        /**
         * Duplication des campagnes pour une autre année
         */
        $login = new Login;
        try {
            if (count($_POST["campaigns"]) == 0) {
                throw new PpciException("Aucune campagne n'a été sélectionnée");
            }
            if (!$_POST["annee"] > 2000) {
                throw new PpciException("L'année n'a pas été renseignée ou est erronée");
            }
            foreach ($_POST["campaigns"] as $campagne_id) {
                $oldcamp = $this->dataclass->lireWithMasseEau($campagne_id);
                if (!$oldcamp["campagne_id"] > 0) {
                    throw new PpciException(sprintf("La campagne %s n'a pas pu être lue dans la base de données", $campagne_id));
                }
                /**
                 * Search if the new campaign exists
                 */
                $newcamp = $this->dataclass->search($oldcamp["fk_masse_eau"], $_POST["annee"], $oldcamp["saison"]);
                if ($newcamp["campagne_id"] > 0) {
                    throw new PpciException(sprintf("La campagne %s existe déjà", $newcamp["campagne_nom"]));
                } else {
                    $newcamp = $oldcamp;
                    $newcamp["campagne_id"] = 0;
                    $newcamp["annee"] = $_POST["annee"];
                    $newcamp["campagne_nom"] = $oldcamp["masse_eau"] . "_" . $oldcamp["saison"] . "_" . $_POST["annee"];
                    $this->id = $this->dataclass->ecrire($newcamp);
                    if (!$this->id > 0) {
                        throw new PpciException("Un problème est survenu lors de l'écriture de la campagne");
                    }
                    /**
                     * Add the acllogins
                     */

                    $login->setLogins($this->id, $login->getOnlyLoginsFromCampagne($oldcamp["campagne_id"]));
                }
            }
            $this->message->set("Opération effectuée");
            return true;
        } catch (PpciException $e) {
            $this->message->set("Une erreur est survenue pendant la duplication", true);
            $this->message->set($e->getMessage());
            return false;
        }
    }
    function campagneInitSelectList($vue)
    {
        $vue->set($this->dataclass->getSaisons(), "saisons");
        $vue->set($this->dataclass->getAnnees(), "annees");
    }
}
