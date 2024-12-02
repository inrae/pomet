<?php

namespace App\Libraries;

use App\Models\Tracegps;
use App\Models\TraitTable;
use Ppci\Libraries\PpciException;
use Ppci\Libraries\PpciLibrary;
use Ppci\Models\PpciModel;

class ImportGpx extends PpciLibrary
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
        $dataTrait = $trait->read($this->id);
        $this->vue->set($_SESSION["ti_trait"]->translateFromRow($dataTrait), "dataTrait");
        $this->vue->set("traits/importGpx.tpl", "corps");
        return $this->vue->send();
    }
    function selectfile()
    {
        /**
         * Verification du chargement du fichier
         */
        $this->vue = service("Smarty");
        try {
            if ($_FILES["filename"]["size"] > 0) {
                $data = $this->dataclass->parseGpx($_FILES["filename"]["tmp_name"]);
                if (count($data) > 0) {
                    $_SESSION["traces"] = $data;
                    $this->vue->set($data, "traces");
                    $trait = new TraitTable;
                    $dataTrait = $trait->read($this->id);
                    $this->vue = service("Smarty");
                    $this->vue->set($_SESSION["ti_trait"]->translateFromRow($dataTrait), "dataTrait");
                    $this->vue->set("traits/importGpx.tpl", "corps");
                    return $this->vue->send();
                } else {
                    throw new PpciException("Impossible de lire les traces dans le fichier");
                }
            } else {
                throw new PpciException("Le fichier n'a pas pu être téléchargé dans le serveur");
            }
        } catch (PpciException $e) {
            $this->message->set($e->getMessage(), true);
            $this->message->setSyslog($e->getMessage());
            return false;
        }
    }
    function exec()
    {
        /**
         * Declenchement de l'import - creation de la trace
         */
        try {
            $this->dataclass->generateTrace($_SESSION["traces"], $_REQUEST["tracenum"], $this->id);
            $this->message->set("Importation du fichier GPX terminée");
            unset($_SESSION["traces"]);
            return true;
        } catch (PpciException $e) {
            $this->message->set($e->getMessage(), true);
            $this->message->setSyslog($e->getMessage());
            return false;
        }
    }
}
