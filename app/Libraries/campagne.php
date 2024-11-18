<?php 
namespace App\Libraries;

use Ppci\Libraries\PpciException;
use Ppci\Libraries\PpciLibrary;
use Ppci\Models\PpciModel;

class Xx extends PpciLibrary { 
    /**
     * @var xx
     */
    protected PpciModel $this->dataclass;
    private $keyName;

    function __construct()
    {
        parent::__construct();
        $this->dataclass = new XXX();
        $keyName = "xxx_id";
        if (isset($_REQUEST[$this->keyName])) {
            $this->id = $_REQUEST[$this->keyName];
        }
    }

/**
 * @author Eric Quinton
 * @copyright Copyright (c) 2015, IRSTEA / Eric Quinton
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 *  Creation 16 oct. 2015
 */
require_once 'modules/classes/campagne.class.php';
require_once 'modules/campagne/campagne.functions.php';
$this->dataclass = new Campagne;
$keyName = "campagne_id";
$this->id = $_REQUEST[$keyName];


    function list(){
$this->vue=service('Smarty');
        /*
		 * Display the list of all records of the table
		 */
        $_SESSION["searchCampagne"]->setParam($_REQUEST);
        $dataSearch = $_SESSION["searchCampagne"]->getParam();
        if ($_SESSION["searchCampagne"]->isSearch() == 1) {
            $this->vue->set($this->dataclass->getListFromParam($dataSearch), "data");
            $this->vue->set(1, "isSearch");
        }
        campagneInitSelectList($this->dataclass);
        $this->vue->set($dataSearch, "dataSearch");
        $this->vue->set("campagne/campagneList.tpl", "corps");
        $masse_eau = new MasseEau;
        $this->vue->set($masse_eau->getListe(2), "masse_eau");
        }
    function change(){
$this->vue=service('Smarty');
        /*
		 * open the form to modify the record
		 * If is a new record, generate a new record with default value :
		 * $_REQUEST["idParent"] contains the identifiant of the parent record
		 */
        $this->dataRead( $this->id, "campagne/campagneChange.tpl");
        campagneInitSelectList($this->dataclass);
        require_once 'modules/classes/experimentation.class.php';
        $experimentation = new Experimentation;
        $this->vue->set($experimentation->getListe(2), "experimentation");
        $masse_eau = new MasseEau;
        $this->vue->set($masse_eau->getListe(2), "masse_eau");
        $personne = new Personne;
        $this->vue->set($personne->getListe(2), "personne");
        /*
         * Ajout de la selection des logins autorises
         */
        require_once 'modules/classes/login.class.php';
        $login = new Login($bdd_gacl, $ObjetBDDParam);
        $this->vue->set($login->getLoginsFromCampagne($this->id), "logins");
        }
        function write() {
    try {
            $this->id = $this->dataWrite($_REQUEST);
            if ($this->id > 0) {
                $_REQUEST[$this->keyName] = $this->id;
                return $this->display();
            } else {
                return $this->change();
            }
        } catch (PpciException) {
            return $this->change();
        }
    }
        /*
		 * write record in database
		 */
        $this->id = dataWrite($this->dataclass, $_REQUEST);
        if (!is_array($_REQUEST["acllogin_id"]) && strlen($_REQUEST["acllogin_id"]) > 0) {
            /**
             * Nothing to do
             */
        } else {
            if ($this->id > 0) {
                $_REQUEST[$keyName] = $this->id;
                /*
                 * Mise a jour des logins rattaches
                 */
                require_once 'modules/classes/login.class.php';
                $login = new Login($bdd_gacl, $ObjetBDDParam);
                $login->setLogins($this->id, $_REQUEST["acllogin_id"]);
            }
        }
        }
        function delete() {
        /*
		 * delete record
		 */
        /*
         * Suppression du rattachement des logins a la campagne
         */
        try {
            require_once 'modules/classes/login.class.php';
            $login = new Login($bdd_gacl, $ObjetBDDParam);
            $login->purgeLoginFromCampagne($this->id);
                   try {
            $this->dataDelete($this->id);
            return $this->list();
        } catch (PpciException $e) {
            return $this->change();
        }
        } catch (Exception $e) {
            $this->message->set("La suppression de la campagne a echoué");
        }
        }
        function searchTrait() {
        /*
         * Recherche les campagnes pour les utilisateurs, avec les parametres de recherche adequats
         */

        $this->vue->set($_SESSION["ti_campagne"]->translateList($this->dataclass->getListFromUser($_SESSION["login"], array(), array(
            "masse_eau_id" => $_REQUEST["masse_eau_id"],
            "annee" => $_REQUEST["annee"],
            "saison" => $_REQUEST["saison"]
        ))));
        }
        function duplicate() {
        /**
         * Duplication des campagnes pour une autre année
         */
        require_once 'modules/classes/login.class.php';
        $login = new Login($bdd_gacl, $ObjetBDDParam);
        try {
            if (count($_POST["campaigns"]) == 0) {
                throw new CampaignException("Aucune campagne n'a été sélectionnée");
            }
            if (!$_POST["annee"] > 2000) {
                throw new CampaignException("L'année n'a pas été renseignée ou est erronée");
            }
            foreach ($_POST["campaigns"] as $campagne_id) {
                $oldcamp = $this->dataclass->lireWithMasseEau($campagne_id);
                if (!$oldcamp["campagne_id"] > 0) {
                    throw new CampaignException(sprintf("La campagne %s n'a pas pu être lue dans la base de données", $campagne_id));
                }
                /**
                 * Search if the new campaign exists
                 */
                $newcamp = $this->dataclass->search($oldcamp["fk_masse_eau"], $_POST["annee"], $oldcamp["saison"]);
                if ($newcamp["campagne_id"] > 0) {
                    throw new CampaignException(sprintf("La campagne %s existe déjà", $newcamp["campagne_nom"]));
                } else {
                    $newcamp = $oldcamp;
                    $newcamp["campagne_id"] = 0;
                    $newcamp["annee"] = $_POST["annee"];
                    $newcamp["campagne_nom"] = $oldcamp["masse_eau"]."_".$oldcamp["saison"]."_".$_POST["annee"];
                    $this->id = $this->dataclass->ecrire($newcamp);
                    if (!$this->id > 0) {
                        throw new CampaignException("Un problème est survenu lors de l'écriture de la campagne");
                    }
                    /**
                     * Add the acllogins
                     */

                    $login->setLogins($this->id, $login->getOnlyLoginsFromCampagne($oldcamp["campagne_id"]));
                }
            }
            $this->message->set("Opération effectuée");
            $module_coderetour = 1;
        } catch (Exception $e) {
            $this->message->set("Une erreur est survenue pendant la duplication", true);
            $this->message->set($e->getMessage());
            $module_coderetour = -1;
        }
        }
}
