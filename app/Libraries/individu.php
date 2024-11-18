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
$this->dataclass = new Individu;
$keyName = "ind_id";
$this->id = $_SESSION["ti_individu"]->getValue($_REQUEST[$keyName]);


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
        $data["ind_id"] = $this->id;
        $data["fk_ech_id"] = $_SESSION["ti_echantillon"]->getValue($_REQUEST["fk_ech_id"]);
        $this->id = dataWrite($this->dataclass, $data);
        if ($this->id > 0) {
            $_REQUEST[$keyName] = $_SESSION["ti_individu"]->setValue($this->id);
            /*
             * Mise a jour le cas echeant de l'echantillon (nombre ou masse totale)
             */
            $echantillon = new Echantillon;
            $echantillon->updateNbTotal($data["fk_ech_id"]);
        }
        $_REQUEST["ind_id"] = 0;
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
        $_REQUEST["ind_id"] = 0;
        }
        function export() {
        $dataSearch = $_SESSION["searchTrait"]->getParam();
        if ($_SESSION["searchTrait"]->isSearch() == 1) {
            $data = $this->dataclass->getListForExport($dataSearch);
            if (!empty($data)) {
                $this->vue->set($data);
                $this->vue->setFilename("pomet_individus.csv");
            } else {
                unset($this->vue);
                $module_coderetour = -1;
                $this->message->set("Pas de dossiers ou d'individus à exporter");
            }
        }
        }
}
?>
