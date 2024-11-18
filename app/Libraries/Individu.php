<?php

namespace App\Libraries;

use App\Models\Echantillon;
use App\Models\Individu as ModelsIndividu;
use Ppci\Libraries\PpciException;
use Ppci\Libraries\PpciLibrary;

class Individu extends PpciLibrary
{

    function __construct()
    {
        parent::__construct();
        $this->dataclass = new ModelsIndividu;
        translateIdInstanciate("ti_individu");
        translateIdInstanciate("ti_echantillon");
        $this->keyName = "ind_id";
        if (isset($_REQUEST[$this->keyName])) {
            $this->id = $_SESSION["ti_individu"]->getValue($_REQUEST[$this->keyName]);
        }
        return $this->vue->send();
    }
    function write()
    {
        try {
            $data = $_REQUEST;
            $data["ind_id"] = $this->id;
            $data["fk_ech_id"] = $_SESSION["ti_echantillon"]->getValue($_REQUEST["fk_ech_id"]);
            $this->id = $this->dataWrite($data);
            if ($this->id > 0) {
                $_REQUEST[$this->keyName] = $_SESSION["ti_individu"]->setValue($this->id);
                /**
                 * Mise a jour le cas echeant de l'echantillon (nombre ou masse totale)
                 */
                $echantillon = new Echantillon;
                $echantillon->updateNbTotal($data["fk_ech_id"]);
                $_REQUEST["ind_id"] = 0;
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
            $_REQUEST["ind_id"] = 0;
            return true;
        } catch (PpciException) {
            return false;
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
                $this->vue->setFilename("pomet_individus.csv");
                $this->vue->send();
            } else {
                unset($this->vue);
                $this->message->set("Pas de dossiers ou d'individus à exporter");
            }
        }
        return false;
    }
}
