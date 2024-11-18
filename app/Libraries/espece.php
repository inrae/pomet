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
 *  Creation 15 oct. 2015
 */
require_once 'modules/classes/espece.class.php';
require_once 'modules/parametre/espece.functions.php';
$this->dataclass = new Espece;
$keyName = "espece_id";
$this->id = $_REQUEST[$keyName];

    function list(){
$this->vue=service('Smarty');
		/*
		 * Display the list of all records of the table
		 */
		$_SESSION["searchEspece"]->setParam($_REQUEST);
        $dataSearch = $_SESSION["searchEspece"]->getParam();
        if ($_SESSION["searchEspece"]->isSearch() == 1) {
            $this->vue->set($this->dataclass->getListFromParam($dataSearch), "data");
            $this->vue->set(1, "isSearch");
        }
        especeInitDropdownlist($this->dataclass, $dataSearch);
        $this->vue->set($dataSearch, "dataSearch");
        $this->vue->set("parametre/especeList.tpl", "corps");
        }
    function display(){
$this->vue=service('Smarty');
		/*
		 * Display the detail of the record
		 */
		$data = $this->dataclass->lire($this->id);
        $this->vue->set($data, "data");
        $this->vue->set("parametre/especeDisplay.tpl", "corps");
        $guilde = new Guilde;
        $this->vue->set($guilde->lire($this->id), "guilde");
        $wiser = new Wiser;
        $this->vue->set($wiser->lire($this->id), "wiser");
        $size = new Size;
        $this->vue->set($size->getFromName($data["nom"]), "size");

        }
    function change(){
$this->vue=service('Smarty');
		/*
		 * open the form to modify the record
		 * If is a new record, generate a new record with default value :
		 * $_REQUEST["idParent"] contains the identifiant of the parent record
		 */
		$data = $this->dataRead( $this->id, "parametre/especeChange.tpl");
        $guilde = new Guilde;
        $this->vue->set($guilde->lire($this->id), "guilde");
        $wiser = new Wiser;
        $this->vue->set($wiser->lire($this->id), "wiser");
        especeInitDropdownlist($this->dataclass, $data);
        $size = new Size;
        $this->vue->set($size->getFromName($data["nom"]), "size");
        $swimming = new Swimming;
        $this->vue->set($swimming->getListe(2), "swimming");
        $resilience = new Resilience;
        $this->vue->set($resilience->getListe(1), "resilience");
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
        if ($this->id > 0) {
            $_REQUEST[$keyName] = $this->id;
            /*
             * Assignation du nom d'espece pour la guilde wiser
             */
            $_REQUEST["name"] = $_REQUEST["nom"];
            $guilde = new Guilde;
            $wiser = new Wiser;
            if ($_REQUEST["species_id"] > 0) {
                $size = new Size;
                $size->ecrire($_REQUEST);
            }
            $guilde->ecrire($_REQUEST);
            $wiser->ecrire($_REQUEST);
        }
        }
        function delete() {
		/*
		 * delete record
		 */
		       try {
            $this->dataDelete($this->id);
            return $this->list();
        } catch (PpciException $e) {
            return $this->change();
        }
        }
        function getValues() {
		/*
		 * Retourne la liste des valeurs uniques du champ fourni en parametre,
		 * au format json
		 */
		$this->vue->set($this->dataclass->getUniqueValuesFromParent($_REQUEST["field"], $_REQUEST["parentField"], $_REQUEST["value"]));
        }
        function search() {
		/*
		 * Retourne la liste des especes correspondant au nom fourni,
		 * au format json
		 */
		$this->vue->set($this->dataclass->getListFromParam(array(
            "nom" => $_REQUEST["nom"]
        )));
        }
        function export() {
        $dataSearch = $_SESSION["searchEspece"]->getParam();
        if ($_SESSION["searchEspece"]->isSearch() == 1) {
            $data = $this->dataclass->getListForExport($dataSearch);
            if (!empty($data)) {
                $this->vue->set($data);
                $this->vue->setFilename("pomet_especes.csv");
            } else {
                $this->message->set("Pas d'enregistrements disponibles pour l'export");
                $module_coderetour = -1;
                unset ($this->vue);
            }
        }
        }
}

?>
