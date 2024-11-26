<?php

namespace App\Libraries;

use App\Models\Tracegps;
use App\Models\TraitTable;
use Ppci\Libraries\PpciException;
use Ppci\Libraries\PpciLibrary;

class ImportGpsCsv extends PpciLibrary
{

    function __construct()
    {
        parent::__construct();
        $this->dataclass = new Tracegps;
        $this->keyName = "trait_id";
        translateIdInstanciate("ti_trait");
        if (isset($_REQUEST[$this->keyName])) {
            $this->id = $_SESSION["ti_trait"]->getValue($_REQUEST["trait_id"]);
        }
    }
    function display()
    {
        $this->vue = service('Smarty');
        $trait = new TraitTable;
        $dataTrait = $trait->lire($this->id);
        $this->vue->set($_SESSION["ti_trait"]->translateFromRow($dataTrait), "dataTrait");
        $this->vue->set("traits/importGpsCsv.tpl", "corps");
        return $this->vue->send();
    }
    function exec()
    {
        /**
         * Verification du chargement du fichier
         */
        try {
            if ($_FILES["filename"]["size"] > 0) {
                $this->dataclass->importCsv($this->id, $_FILES["filename"]["tmp_name"], $_POST["separator"]);
                $this->message->set("Importation de la trace effectuée !");
                return true;
            } else {
                throw new PpciException("Le fichier n'a pas pu être téléchargé sur le serveur");
            }
        } catch (PpciException $e) {
            $this->message->set("Impossible d'importer la trace", true);
            $this->message->set($e->getMessage(), true);
            $this->message->setSyslog($e->getMessage());
            return false;
        }
    }
}
