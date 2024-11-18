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
 * @author : quinton
 * @date : 11 déc. 2015
 * @encoding : UTF-8
 * (c) 2015 - All rights reserved
 */
include_once 'modules/classes/trait.class.php';
$this->dataclass = new Echantillon;
$keyName = "ech_id";
$this->id = $_SESSION["ti_echantillon"]->getValue($_REQUEST[$keyName]);
if (isset($_REQUEST["fk_trait_id"])) {
    $trait_id = $_SESSION["ti_trait"]->getValue($_REQUEST["fk_trait_id"]);
} else {
    $trait_id = $_SESSION["ti_trait"]->getValue($_REQUEST["trait_id"]);
}

    function list(){
$this->vue=service('Smarty');
        $this->vue->set($this->dataclass->getListeFromTrait($trait_id), "data");
        $this->vue->set("trait/echantillonList.tpl", "corps");
        }
    function change(){
$this->vue=service('Smarty');
		/*
		 * open the form to modify the record
		 * If is a new record, generate a new record with default value :
		 * $_REQUEST["idParent"] contains the identifiant of the parent record
		 */
		$dataEchan = $this->dataRead( $this->id, "traits/echantillonChange.tpl", $trait_id);
        $dataEchan = $_SESSION["ti_echantillon"]->translateFromRow($dataEchan);
        $dataEchan = $_SESSION["ti_trait"]->translateFromRow($dataEchan);
        $this->vue->set($dataEchan, "dataEchan");
        /*
         * Lecture du trait pour le cartouche
         */
        $traitTable = new TraitTable;
        $dataTrait = $traitTable->getDetail($trait_id);
        $dataTrait = $_SESSION["ti_trait"]->translateFromRow($dataTrait);
        $this->vue->set($dataTrait, "dataTrait");
        /*
         * Lecture des individus rattaches
         */
        $individu = new Individu;
        $dataIndiv = $_SESSION["ti_individu"]->translateList($individu->getListFromEchantillon($this->id));
        $dataIndiv = $_SESSION["ti_echantillon"]->translateList($dataIndiv);
        $this->vue->set($dataIndiv, "individus");
        /*
         * Lecture des donnees d'un individu, pour masque de saisie
         */
        if ($_REQUEST["ind_id"] > 0) {
            $ind_id = $_SESSION["ti_individu"]->getValue($_REQUEST["ind_id"]);
            /*
             * Assignation à Smarty pour affichage de l'enregistrement selectionne dans la liste
             */
            $this->vue->set($_REQUEST["ind_id"], "ind_id");
        } else {
            $ind_id = 0;
        }
        $dataIndiv = $_SESSION["ti_individu"]->translateFromRow($individu->lire($ind_id, true, $this->id));
        $dataIndiv = $_SESSION["ti_echantillon"]->translateFromRow($dataIndiv);
        $this->vue->set($dataIndiv, "individu");
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
		 * Preparation des donnees a ecrire
		 */
		$data = $_REQUEST;
        $data["ech_id"] = $this->id;
        $data["fk_trait_id"] = $_SESSION["ti_trait"]->getValue($_REQUEST["fk_trait_id"]);
        $this->id = dataWrite($this->dataclass, $data);
        if ($this->id > 0) {
            $_REQUEST[$keyName] = $_SESSION["ti_echantillon"]->setValue($this->id);
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
        function export() {
        $dataSearch = $_SESSION["searchTrait"]->getParam();
        if ($_SESSION["searchTrait"]->isSearch() == 1) {
            $data = $this->dataclass->getListForExport($dataSearch);
            if (!empty($data) ) {
                $this->vue->set($data);
                $this->vue->setFilename("pomet_echantillons.csv");
            } else {
                unset($this->vue);
                $this->message->set("Pas de traits ou d'informations a exporter");
                $module_coderetour = - 1;
            }
        }
        }
}
?>
