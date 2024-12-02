<?php

namespace App\Libraries;

use App\Models\Echantillon as ModelsEchantillon;
use App\Models\Individu;
use App\Models\TraitTable;
use Ppci\Libraries\PpciException;
use Ppci\Libraries\PpciLibrary;

class Echantillon extends PpciLibrary
{

        private int $trait_id;

    function __construct()
    {
        parent::__construct();
        $this->dataclass = new ModelsEchantillon;
        $this->keyName = "ech_id";
        translateIdInstanciate("ti_trait");
        translateIdInstanciate("ti_echantillon");
        translateIdInstanciate("ti_individu");
        if (isset($_REQUEST[$this->keyName])) {
            $this->id = $_SESSION["ti_echantillon"]->getValue($_REQUEST[$this->keyName]);
        }
        if (isset($_REQUEST["fk_trait_id"])) {
            $this->trait_id = $_SESSION["ti_trait"]->getValue($_REQUEST["fk_trait_id"]);
        } else {
            $this->trait_id = $_SESSION["ti_trait"]->getValue($_REQUEST["trait_id"]);
        }
    }
    function list()
    {
        $this->vue = service('Smarty');
        $this->vue->set($this->dataclass->getListeFromTrait($this->trait_id), "data");
        $this->vue->set("trait/echantillonList.tpl", "corps");
        return $this->vue->send();
    }
    function change()
    {
        $this->vue = service('Smarty');
        /**
         * open the form to modify the record
         * If is a new record, generate a new record with default value :
         * $_REQUEST["idParent"] contains the identifiant of the parent record
         */
        $dataEchan = $this->dataRead($this->id, "traits/echantillonChange.tpl", $this->trait_id);
        $dataEchan["trait_id"] = $this->trait_id;
        $dataEchan = $_SESSION["ti_echantillon"]->translateFromRow($dataEchan);
        $dataEchan = $_SESSION["ti_trait"]->translateFromRow($dataEchan);
        $dataEchan["fk_trait_id"] = $dataEchan["trait_id"];
        $this->vue->set($dataEchan, "dataEchan");
        /**
         * Lecture du trait pour le cartouche
         */
        $traitTable = new TraitTable;
        $dataTrait = $traitTable->getDetail($this->trait_id);
        $dataTrait = $_SESSION["ti_trait"]->translateFromRow($dataTrait);
        $this->vue->set($dataTrait, "dataTrait");
        /**
         * Lecture des individus rattaches
         */
        $individu = new Individu;
        $dataIndiv = $_SESSION["ti_individu"]->translateList($individu->getListFromEchantillon($this->id));
        $dataIndiv = $_SESSION["ti_echantillon"]->translateList($dataIndiv);
        foreach($dataIndiv as $k=>$v) {
            $dataIndiv[$k]["fk_ech_id"] = $v["ech_id"];
        }
        $this->vue->set($dataIndiv, "individus");
        /**
         * Lecture des donnees d'un individu, pour masque de saisie
         */
        if ($_REQUEST["ind_id"] > 0) {
            $ind_id = $_SESSION["ti_individu"]->getValue($_REQUEST["ind_id"]);
            /**
             * Assignation à Smarty pour affichage de l'enregistrement selectionne dans la liste
             */
            $this->vue->set($_REQUEST["ind_id"], "ind_id");
        } else {
            $ind_id = 0;
        }
        $dataIndiv = $_SESSION["ti_individu"]->translateFromRow($individu->read($ind_id, true, $this->id));
        $dataIndiv = $_SESSION["ti_echantillon"]->translateFromRow($dataIndiv);
        $this->vue->set($dataIndiv, "individu");
        return $this->vue->send();
    }
    function write()
    {
        try {
            /**
             * Preparation des donnees a ecrire
             */
            $data = $_REQUEST;
            $data["ech_id"] = $this->id;
            $data["fk_trait_id"] = $_SESSION["ti_trait"]->getValue($_REQUEST["fk_trait_id"]);
            $this->id = $this->dataWrite($data);
            if ($this->id > 0) {
                $_REQUEST[$this->keyName] = $_SESSION["ti_echantillon"]->setValue($this->id);
                return true;
            } else {
                return false;
            }
        } catch (PpciException) {
            return false;
        }
    }
    function delete()
    {
        /**
         * delete record
         */
        try {
            $this->dataDelete($this->id);
            return $this->list();
        } catch (PpciException $e) {
            return $this->change();
        }
    }
    function export()
    {
        $dataSearch = $_SESSION["searchTrait"]->getParam();
        if ($_SESSION["searchTrait"]->isSearch() == 1) {
            $data = $this->dataclass->getListForExport($dataSearch);
            if (!empty($data)) {
                $this->vue = service("CsvView");
                $this->vue->set($data);
                $this->vue->setFilename("pomet_echantillons.csv");
                $this->vue->send();
            } else {
                unset($this->vue);
                $this->message->set("Pas de traits ou d'informations a exporter");
                return false;
            }
        }
        return false;
    }
}
