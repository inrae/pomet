<?php

namespace App\Libraries;

use App\Libraries\Campagne as LibrariesCampagne;
use App\Models\Campagne;
use App\Models\Echantillon;
use App\Models\Experimentation;
use App\Models\MasseEau;
use App\Models\Materiel;
use App\Models\Salinite;
use App\Models\Tracegps;
use App\Models\TraitTable;
use Ppci\Libraries\PpciException;
use Ppci\Libraries\PpciLibrary;
use Ppci\Models\PpciModel;
use ZipArchive;

class Traits extends PpciLibrary
{
    private $campagne_id;
    /**
     * @var TraitTable
     */
    public PpciModel $dataclass;

    function __construct()
    {
        parent::__construct();

        $this->dataclass = new TraitTable();
        translateIdInstanciate("ti_trait");
        translateIdInstanciate("ti_campagne");
        translateIdInstanciate("ti_echantillon");
        $this->keyName = "trait_id";
        if (isset($_REQUEST[$this->keyName])) {
            $this->id = $_SESSION["ti_trait"]->getValue($_REQUEST[$this->keyName]);
        }
        $this->campagne_id = $_SESSION["ti_campagne"]->getFromListForAllValue($_REQUEST["campagne_id"]);
    }

    function list()
    {
        $this->vue = service('Smarty');
        /**
         * Display the list of all records of the table
         */
        $dataSearch = $_REQUEST;
        /**
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
        if (!isset($_SESSION["searchTrait"])) {
            $_SESSION["searchTrait"] = new SearchTrait;
        }
        $_SESSION["searchTrait"]->setParam($dataSearch);
        $dataSearch = $_SESSION["searchTrait"]->getParam();
        /**
         * Recherche des campagnes
         */
        $campagneLib = new LibrariesCampagne;
        $campagneLib->campagneInitSelectList($this->vue);
        $masse_eau = new MasseEau;
        $this->vue->set($masse_eau->getListe(2), "masse_eau");
        $campagne = new Campagne;
        $dataCampagne = $campagne->getListFromUser($_SESSION["login"], $dataSearch["campagne_id"], $dataSearch);
        if ($_SESSION["searchTrait"]->isSearch() == 1 && ($_SESSION["searchTrait"]->hasCampagneSelected() || $dataSearch["uid"] > 0)) {
            $data = $this->dataclass->getListFromParam($dataSearch);
            $data = $_SESSION["ti_trait"]->translateList($data);
            $data = $_SESSION["ti_campagne"]->translateList($data);
            $this->vue->set($data, "data");
            $this->vue->set(1, "isSearch");
        }
        $this->vue->set($dataSearch, "dataSearch");
        $this->vue->set("traits/traitList.tpl", "corps");
        $this->vue->set($_SESSION["ti_campagne"]->translateList($dataCampagne), "campagne");
        return $this->vue->send();
    }
    function display()
    {
        $this->vue = service('Smarty');
        /**
         * Display the detail of the record
         */
        $data = $this->dataclass->getDetail($this->id);
        $data = $_SESSION["ti_trait"]->translateFromRow($data);
        $data = $_SESSION["ti_campagne"]->translateFromRow($data);
        $this->vue->set($data, "data");
        $this->vue->set(calcul_distance_gps($data["pos_deb_long_dd"], $data["pos_deb_lat_dd"], $data["pos_fin_long_dd"], $data["pos_fin_lat_dd"]), "distance_calculee");
        /**
         * Recherche des echantillons associes
         */
        $echantillon = new Echantillon;
        $dataEchantillon = $echantillon->getListeFromTrait($this->id);
        $dataEchantillon = $_SESSION["ti_trait"]->translateList($dataEchantillon);
        $dataEchantillon = $_SESSION["ti_echantillon"]->translateList($dataEchantillon);
        $this->vue->set($dataEchantillon, "dataEchantillon");
        /**
         * Recherche de la trace GPS
         */
        $tracegps = new Tracegps;
        $this->vue->set($tracegps->getTrace($this->id)["ligne_geom"], "tracegps");
        $this->vue->htmlVars[] = "tracegps";
        $this->vue->set($tracegps->calculLength($this->id), "gps_trait_length");
        $this->vue->set("traits/traitDisplay.tpl", "corps");
        return $this->vue->send();
    }
    function change()
    {
        $this->vue = service('Smarty');
        /**
         * Teste si on est en creation, avec plus d'une campagne selectionnee
         */
        $campagnes = $_SESSION["searchTrait"]->getParam()["campagne_id"];
        if (is_array($campagnes) && count($campagnes) > 1) {
            $this->message->set("La création ou la modification d'un trait n'est pas possible si plusieurs campagnes sont sélectionnées", true);
            return false;
        } else {
            /**
             * Recherche des campagnes
             */
            $campagne = new Campagne;
            $dataCampagne = $campagne->getListFromArray($_SESSION["searchTrait"]->getParam()["campagne_id"]);
            /**
             * Verification que l'experimentation soit correctement renseignee
             */
            if ($dataCampagne[0]["experimentation_id"] > 0) {
                $data = $this->dataRead($this->id, "traits/traitChange.tpl");
                /**
                 * Prepositionnement de la carte
                 */
                $this->vue->set($dataCampagne[0]["longitude"], "mapDefaultX");
                $this->vue->set($dataCampagne[0]["latitude"], "mapDefaultY");
                $this->vue->set($dataCampagne[0]["zoom"], "mapDefaultZoom");
                /**
                 * Recherche des campagnes
                 */
                $this->vue->set($_SESSION["ti_campagne"]->translateList($dataCampagne), "campagne");
                /**
                 * Recherche des donnees de l'experimentation
                 */
                $experimentation = new Experimentation;
                $dataExp = $experimentation->read($dataCampagne[0]["experimentation_id"]);
                $this->vue->set($dataExp, "experimentation");
                /**
                 * Recheche du materiel associe
                 */
                $materiel = new Materiel;
                $this->vue->set(
                    $materiel->getListFromExperimentation(
                        $dataCampagne[0]["experimentation_libelle"]
                    ),
                    "materiel"
                );
                /**
                 * Recherche de la salinite
                 */
                $salinite = new Salinite;
                $this->vue->set($salinite->getListe(1), "salinite");

                /**
                 * Recherche de la trace GPS
                 */
                $tracegps = new Tracegps;
                $this->vue->set($tracegps->getTrace($this->id)["ligne_geom"], "tracegps");
                $this->vue->htmlVars[] = "tracegps";

                $data = $_SESSION["ti_trait"]->translateRow($data);
                $data = $_SESSION["ti_campagne"]->translateRow($data);
                $this->vue->set($data, "data");
            } else {
                $this->message->set("La création ou la modification d'un trait n'est pas possible : l'expérimentation n'est pas renseignée pour la campagne considérée", true);
                return false;
            }
            return $this->vue->send();
        }
    }
    function write()
    {
        try {
            /**
             * Preparation des donnees a ecrire
             */
            $data = $_REQUEST;
            $data["trait_id"] = $this->id;
            $data["fk_campagne_id"] = $_SESSION["ti_campagne"]->getValue($_REQUEST["fk_campagne_id"]);
            $this->id = $this->dataWrite($data);
            if ($this->id > 0) {
                $_REQUEST[$this->keyName] = $_SESSION["ti_trait"]->setValue($this->id);
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
        } catch (PpciException $e) {
            return false;
        }
    }
    function export()
    {
        if (!isset($_SESSION["searchTrait"])) {
            $_SESSION["searchTrait"] = new SearchTrait;
        }
        $dataSearch = $_SESSION["searchTrait"]->getParam();
        if ($_SESSION["searchTrait"]->isSearch() == 1) {
            $data = $this->dataclass->getListForExport($dataSearch);
            if (!empty($data)) {
                $this->vue = service("CsvView");
                $this->vue->set($data);
                $this->vue->setFilename("pomet_traits.csv");
                $this->vue->send();
            } else {
                $this->message->set("Pas de traits sélectionnés");
            }
        }
        return false;
    }
    function shapeSelect()
    {
        $this->vue = service('Smarty');
        $this->vue->set("traits/shapeSelect.tpl", "corps");
        $experimentation = new Experimentation;
        $this->vue->set($experimentation->getList("experimentation_libelle"), "experimentations");
        if (isset($_REQUEST["experimentation_id"])) {
            $this->vue->set($_REQUEST["experimentation_id"], "experimentation_id");
        } else {
            $this->vue->set(1, "experimentation_id");
        }
        $yearsminmax = $this->dataclass->getYearsMinMax();
        $min = $yearsminmax["yearmin"];
        $max = $yearsminmax["yearmax"];
        $years = [];
        for ($i = $min; $i <= $max; $i++) {
            $years[] = $i;
        }
        $this->vue->set($years, "years");
        if (isset($_REQUEST["yearmin"])) {
            $this->vue->set($_REQUEST["yearmin"], "yearmin");
            $this->vue->set($_REQUEST["yearmax"], "yearmax");
        } else {
            $this->vue->set($min, "yearmin");
            $this->vue->set($max, "yearmax");
        }
        $masseeau = new MasseEau;
        $this->vue->set($masseeau->getList("masse_eau"), "masseseaux");
        return $this->vue->send();
    }
    function shapeExec()
    {
        $shpFolder = $this->appConfig->APP_temp . '/' . $this->appConfig->shpFolder;
        try {
            if (!is_dir($shpFolder)) {
                if (!mkdir($shpFolder)) {
                    throw new PpciException("Impossible de créer le dossier temporaire pour exporter les traces");
                }
            }

            if (!is_numeric($_REQUEST["experimentation_id"]) || !is_numeric($_REQUEST["yearmin"]) || !is_numeric($_REQUEST["yearmax"])) {
                throw new PpciException("Les données en entrée sont erronées");
            }
            $masses = "";
            $hasMasseEau = false;
            $comma = "";
            if (!empty($_REQUEST["masseseaux"])) {
                $hasMasseEau = true;
                foreach ($_REQUEST["masseseaux"] as $masse_eau_id) {
                    if (!is_numeric($masse_eau_id)) {
                        throw new PpciException("Les données en entrée sont erronées");
                    }
                    $masses .= $comma . $masse_eau_id;
                    $comma = ",";
                }
            }
            $sql = "select v.* from v_trait_shp v
            join trait using (trait_id)
            join campagnes on (fk_campagne_id = campagne_id)
            where experimentation_id = " . $_REQUEST["experimentation_id"] . "
            and v.annee between " . $_REQUEST["yearmin"] . " and " . $_REQUEST["yearmax"];
            if ($hasMasseEau) {
                $sql .= " and fk_masse_eau in ($masses)";
            }
            /**
             * @var App\Config\Database
             */
            $db = config("Database");
            $command = $this->appConfig->pgsql2shp . " -h " . $db->default["hostname"] . " -u " . $db->default["username"] . " -P " . $db->default["password"] . ' -f "' . $shpFolder . '/pomet.shp" ' . $db->default["database"] . ' "' . $sql . '"';
            //printA($command);exit;
            if (!exec($command, $output)) {
                foreach ($output as $error) {
                    $this->message->set($error, true);
                }
                throw new PpciException("Une erreur est survenue pendant la création du shape dans l'espace temporaire du serveur");
            }
            if (!file_exists("$shpFolder/pomet.shp")) {
                throw new PpciException("Le fichier contenant le shape n'a pas été écrit dans l'espace temporaire pour une raison inconnue");
            }
            $zipfile = $this->appConfig->APP_temp . "/" . "pomet.zip";
            $zip = new ZipArchive;
            if (!$zip->open($zipfile, ZipArchive::CREATE)) {
                throw new PpciException("Impossible de créer le fichier zip");
            }
            if (!$zip->addPattern('/\.(?:cpg|dbf|prj|shp|shx)$/', $shpFolder, [
                "remove_all_path" => true,
                ZipArchive::FL_OVERWRITE
            ])) {
                throw new PpciException("Impossible de rajouter les fichiers shp dans le fichier zip");
            }
            $zip->close();
            /**
             * delete files generated
             */
            $folder = opendir($shpFolder);
            while (false !== ($entry = readdir($folder))) {
                $path = $shpFolder . "/" . $entry;
                if (!is_dir($path)) {
                    unlink($path);
                }
            }
            closedir($folder);
            /**
             * @var \Ppci\Libraries\Views\FileView
             */
            $vue = service("FileView");
            $params = [
                "filename" => "pomet.zip",
                "content_type" => "application/zip",
                "tmp_name" => $zipfile
            ];
            $vue->send($params);
        } catch (PpciException $e) {
            $this->message->set($e->getMessage(), true);
            return false;
        }
    }
}
