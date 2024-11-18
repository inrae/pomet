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
 * Created : 20 févr. 2018
 * Creator : quinton
 * Encoding : UTF-8
 * Copyright 2018 - All rights reserved
 */
require_once 'modules/classes/trait.class.php';
require_once 'modules/classes/tracegps.class.php';

$this->dataclass = new Tracegps;
$keyName = "trait_id";
$trait_id = $_SESSION["ti_trait"]->getValue($_REQUEST["trait_id"]);

    function display(){
$this->vue=service('Smarty');
        try {
            $trait = new TraitTable;
            $dataTrait = $trait->lire($trait_id);
            $this->vue->set($_SESSION["ti_trait"]->translateFromRow($dataTrait), "dataTrait");
            $this->vue->set("traits/importGpx.tpl", "corps");
        } catch (Exception $e) {
            if ($OBJETBDD_debugmode > 0) {
                $this->message->set($this->dataclass->getErrorData(1));
            } else {
                $this->message->set($LANG["message"][37]);
            }
            $this->message->setSyslog($e->getMessage());
            $module_coderetour = -1;
        }
        }
        function selectfile() {
        /*
         * Verification du chargement du fichier
         */
        try {
            if ($_FILES["filename"]["size"] > 0) {
                $data = $this->dataclass->parseGpx($_FILES["filename"]["tmp_name"]);
                if (count($data) > 0) {
                    $_SESSION["traces"] = $data;
                    $this->vue->set($data, "traces");
                    $trait = new TraitTable;
                    $dataTrait = $trait->lire($trait_id);
                    $this->vue->set($_SESSION["ti_trait"]->translateFromRow($dataTrait), "dataTrait");
                    $this->vue->set("traits/importGpx.tpl", "corps");
                } else {
                    $this->message->set("Impossible de lire les traces dans le fichier");
                    $module_coderetour = -1;
                }
            }
        } catch (Exception $e) {
            $this->message->set("Impossible de lire le fichier GPX fourni");
            $this->message->setSyslog($e->getMessage());
            $module_coderetour = -1;
        }
        }
        function exec() {
        /*
         * Declenchement de l'import - creation de la trace
         */
        try {
            $this->dataclass->generateTrace($_SESSION["traces"], $_REQUEST["tracenum"], $trait_id);
            $this->message->set("Importation du fichier GPX terminée");
            unset($_SESSION["traces"]);
            $module_coderetour = 1;
        } catch (Exception $e) {
            if ($OBJETBDD_debugmode > 0) {
                $this->message->set($this->dataclass->getErrorData(1));
            } else {
                $this->message->set($LANG["message"][37]);
            }
            $this->message->setSyslog($e->getMessage());
            $module_coderetour = -1;
        }
        }

}
