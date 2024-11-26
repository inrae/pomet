<?php

namespace App\Libraries;

use App\Models\Experimentation;
use App\Models\Materiel as ModelsMateriel;
use Ppci\Libraries\PpciException;
use Ppci\Libraries\PpciLibrary;
use Ppci\Models\PpciModel;

class Materiel extends PpciLibrary
{

    
    function __construct()
    {
        parent::__construct();
        $this->dataclass = new ModelsMateriel;
        $this->keyName = "materiel_id";
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
        $this->vue->set($this->dataclass->getListe(3), "data");
        $this->vue->set("parametre/materielList.tpl", "corps");
        return $this->vue->send();
    }
    function change()
    {
        $this->vue = service('Smarty');
        $experimentation = new Experimentation;
        $this->vue->set($experimentation->getListe(2), "experimentation");
        $this->vue->set($this->dataclass->getUniqueValues("materiel_type"), "materiel_type");
        $this->dataRead($this->id, "parametre/materielChange.tpl");
        return $this->vue->send();
    }
    function write()
    {
        try {
            $this->id = $this->dataWrite($_REQUEST);
            if ($this->id > 0) {
                $_REQUEST[$this->keyName] = $this->id;
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
            return true;
        } catch (PpciException) {
            return false;
        }
    }
    function export()
    {
        $data = $this->dataclass->getListForExport();
        if (count($data) > 0) {
            $this->vue = service("CsvView");
            $this->vue->set($data);
            $this->vue->setFilename("pomet_materiels.csv");
            $this->vue->send();
        } else {
            $this->message->set("Pas de matériel à exporter");
            return false;
        }
    }
}
