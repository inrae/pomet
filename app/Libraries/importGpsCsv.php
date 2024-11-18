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
        $this->keyName = "xxx_id";
        if (isset($_REQUEST[$this->keyName])) {
            $this->id = $_REQUEST[$this->keyName];
        }
    }
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
      $this->vue->set("traits/importGpsCsv.tpl", "corps");
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
      function exec() {
    /**
         * Verification du chargement du fichier
         */
    try {
      if ($_FILES["filename"]["size"] > 0) {
        $this->dataclass->importCsv($trait_id, $_FILES["filename"]["tmp_name"], $_POST["separator"]);
        $module_coderetour = 1;
        $this->message->set("Importation de la trace effectuée !");
      } else {
        throw new GpsException("Le fichier n'a pas pu être téléchargé sur le serveur");
      }
    } catch (Exception $e) {
      $this->message->set("Impossible d'importer la trace", true);
      $this->message->set($e->getMessage(), true);
      $this->message->setSyslog($e->getMessage());
      $module_coderetour = -1;
    }
    }
}
