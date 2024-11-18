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
include_once 'modules/classes/trait.class.php';
$this->dataclass = new TraitTable;
$keyName = "trait_id";
$this->id = $_SESSION["ti_trait"]->getValue($_REQUEST[$keyName]);
$campagne_id = $_SESSION["ti_campagne"]->getFromListForAllValue($_REQUEST["campagne_id"]);


    function list(){
$this->vue=service('Smarty');
        /*
    * Display the list of all records of the table
    */
        $dataSearch = $_REQUEST;
        /*
    * Gestion des identifiants multiples pour les campagnes
    */
        if (is_array($dataSearch["campagne_id"])) {
            foreach ($dataSearch["campagne_id"] as $k => $v) {
                $dataSearch["campagne_id"][$k] = $_SESSION["ti_campagne"]->getValue($v);
            }
        } else {
            if (isset($dataSearch["campagne_id"])) {
                $dataSearch["campagne_id"] =
                    $_SESSION["ti_campagne"]->getValue($dataSearch["campagne_id"]);
            }
        }
        $_SESSION["searchTrait"]->setParam($dataSearch);
        $dataSearch = $_SESSION["searchTrait"]->getParam();
        /*
    * Recherche des campagnes
    */
        include_once 'modules/classes/campagne.class.php';
        include_once 'modules/campagne/campagne.functions.php';
        $campagne = new Campagne;
        campagneInitSelectList($campagne);
        $masse_eau = new MasseEau;
        $this->vue->set($masse_eau->getListe(2), "masse_eau");
        $dataCampagne = $campagne->getListFromUser($_SESSION["login"], $dataSearch["campagne_id"], $dataSearch);
        if ($_SESSION["searchTrait"]->isSearch() == 1 && $_SESSION["searchTrait"]->hasCampagneSelected()) {
            $data = $this->dataclass->getListFromParam($dataSearch);
            $data = $_SESSION["ti_trait"]->translateList($data);
            $data = $_SESSION["ti_campagne"]->translateList($data);
            $this->vue->set($data, "data");
            $this->vue->set(1, "isSearch");
        }
        $this->vue->set($dataSearch, "dataSearch");
        $this->vue->set("traits/traitList.tpl", "corps");
        $this->vue->set($_SESSION["ti_campagne"]->translateList($dataCampagne), "campagne");
        }
    function display(){
$this->vue=service('Smarty');
        /*
    * Display the detail of the record
    */
        $data = $this->dataclass->getDetail($this->id);
        $data = $_SESSION["ti_trait"]->translateFromRow($data);
        $data = $_SESSION["ti_campagne"]->translateFromRow($data);
        $this->vue->set($data, "data");
        $this->vue->set(calcul_distance_gps($data["pos_deb_long_dd"], $data["pos_deb_lat_dd"], $data["pos_fin_long_dd"], $data["pos_fin_lat_dd"]),"distance_calculee");
        /*
    * Recherche des echantillons associes
    */
        $echantillon = new Echantillon;
        $dataEchantillon = $echantillon->getListeFromTrait($this->id);
        $dataEchantillon = $_SESSION["ti_trait"]->translateList($dataEchantillon);
        $dataEchantillon = $_SESSION["ti_echantillon"]->translateList($dataEchantillon);
        $this->vue->set($dataEchantillon, "dataEchantillon");
        /*
    * Recherche de la trace GPS
    */
        include_once 'modules/classes/tracegps.class.php';
        $tracegps = new Tracegps;
        $this->vue->set($tracegps->getTrace($this->id), "tracegps");
        $this->vue->set($tracegps->calculLength($this->id), "gps_trait_length");
        $this->vue->set("traits/traitDisplay.tpl", "corps");
        }
    function change(){
$this->vue=service('Smarty');
        /*
    * Teste si on est en creation, avec plus d'une campagne selectionnee
    */
        if (count($_SESSION["searchTrait"]->getParam()["campagne_id"]) > 1) {
            $message = "La création ou la modification d'un trait n'est pas possible si plusieurs campagnes sont sélectionnées";
            $module_coderetour = -1;
        } else {
            /*
        * Recherche des campagnes
        */
            include_once 'modules/classes/campagne.class.php';
            $campagne = new Campagne;
            $dataCampagne = $campagne->getListFromArray($_SESSION["searchTrait"]->getParam()["campagne_id"]);
            /*
        * Verification que l'experimentation soit correctement renseignee
        */
            if ($dataCampagne[0]["experimentation_id"] > 0) {
                $data = $this->dataRead( $this->id, "traits/traitChange.tpl");
                /*
            * Prepositionnement de la carte
            */
                $this->vue->set($dataCampagne[0]["longitude"], "mapDefaultX");
                $this->vue->set($dataCampagne[0]["latitude"], "mapDefaultY");
                $this->vue->set($dataCampagne[0]["zoom"], "mapDefaultZoom");
                /*
            * Recherche des campagnes
            */
                $this->vue->set($_SESSION["ti_campagne"]->translateList($dataCampagne), "campagne");
                /*
            * Recherche des donnees de l'experimentation
            */
                include_once 'modules/classes/experimentation.class.php';
                $experimentation = new Experimentation;
                $dataExp = $experimentation->lire($dataCampagne[0]["experimentation_id"]);
                $this->vue->set($dataExp, "experimentation");
                /*
            * Recheche du materiel associe
            */
                include_once 'modules/classes/materiel.class.php';
                $materiel = new Materiel;
                $this->vue->set(
                    $materiel->getListFromExperimentation(
                        $dataCampagne[0]["experimentation_libelle"]
                    ),
                    "materiel"
                );
                /*
            * Recherche de la salinite
            */
                $salinite = new Salinite;
                $this->vue->set($salinite->getListe(1), "salinite");

                /*
            * Recherche s'il existe une trace gps
            */
                include_once 'modules/classes/tracegps.class.php';
                $tracegps = new Tracegps;
                $this->vue->set($tracegps->getTrace($this->id), "tracegps");

                $data = $_SESSION["ti_trait"]->translateRow($data);
                $data = $_SESSION["ti_campagne"]->translateRow($data);
                $this->vue->set($data, "data");
            } else {
                $message = "La création ou la modification d'un trait n'est pas possible : l'expérimentation n'est pas renseignée pour la campagne considérée";
                $module_coderetour = -1;
            }
        }
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
        $data["trait_id"] = $this->id;
        $data["fk_campagne_id"] = $_SESSION["ti_campagne"]->getValue($_REQUEST["fk_campagne_id"]);
        $this->id = dataWrite($this->dataclass, $data);
        if ($this->id > 0) {
            $_REQUEST[$keyName] = $_SESSION["ti_trait"]->setValue($this->id);
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
            if (!empty($data)) {
                $this->vue->set($data);
                $this->vue->setFilename("pomet_traits.csv");
            } else {
                unset($this->vue);
                $this->message->set("Pas de traits sélectionnés");
                $module_coderetour = -1;
            }
        }
        }
}