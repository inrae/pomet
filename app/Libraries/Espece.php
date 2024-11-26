<?php

namespace App\Libraries;

use App\Models\Espece as ModelsEspece;
use App\Models\Guilde;
use App\Models\Resilience;
use App\Models\Size;
use App\Models\Swimming;
use App\Models\Wiser;
use Ppci\Libraries\PpciException;
use Ppci\Libraries\PpciLibrary;
use Ppci\Models\PpciModel;

class Espece extends PpciLibrary
{

    
    function __construct()
    {
        parent::__construct();
        $this->dataclass = new ModelsEspece;
        $this->keyName = "espece_id";
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
        $_SESSION["searchEspece"]->setParam($_REQUEST);
        $dataSearch = $_SESSION["searchEspece"]->getParam();
        if ($_SESSION["searchEspece"]->isSearch() == 1) {
            $this->vue->set($this->dataclass->getListFromParam($dataSearch), "data");
            $this->vue->set(1, "isSearch");
        }
        $this->especeInitDropdownlist($dataSearch);
        $this->vue->set($dataSearch, "dataSearch");
        $this->vue->set("parametre/especeList.tpl", "corps");
        return $this->vue->send();
    }
    function display()
    {
        $this->vue = service('Smarty');
        /**
         * Display the detail of the record
         */
        $data = $this->dataclass->lire($this->id);
        $this->vue->set($data, "data");
        $this->vue->set("parametre/especeDisplay.tpl", "corps");
        $guilde = new Guilde;
        $this->vue->set($guilde->lire($this->id), "guilde");
        $wiser = new Wiser;
        $this->vue->set($wiser->lire($this->id), "wiser");
        $size = new Size;
        $this->vue->set($size->getFromName($data["nom"]), "size");
        return $this->vue->send();
    }
    function change()
    {
        $this->vue = service('Smarty');
        $data = $this->dataRead($this->id, "parametre/especeChange.tpl");
        $guilde = new Guilde;
        $this->vue->set($guilde->lire($this->id), "guilde");
        $wiser = new Wiser;
        $this->vue->set($wiser->lire($this->id), "wiser");
        $this->especeInitDropdownlist($data);
        $size = new Size;
        $this->vue->set($size->getFromName($data["nom"]), "size");
        $swimming = new Swimming;
        $this->vue->set($swimming->getListe(2), "swimming");
        $resilience = new Resilience;
        $this->vue->set($resilience->getListe(1), "resilience");
        return $this->vue->send();
    }
    function write()
    {
        try {
            $this->id = $this->dataWrite($_REQUEST);
            if ($this->id > 0) {
                $_REQUEST[$this->keyName] = $this->id;
                /**
                 * Assignation du nom d'espece pour la guilde wiser
                 */
                $_REQUEST["name"] = $_REQUEST["nom"];
                $guilde = new Guilde;
                $wiser = new Wiser;
                if ($_REQUEST["species_id"] > 0) {
                    $size = new Size;
                    $size->ecrire($_REQUEST);
                }
                $guilde->ecrire($_REQUEST);
                $wiser->ecrire($_REQUEST);
                return $this->display();
            } else {
                return $this->change();
            }
        } catch (PpciException) {
            return $this->change();
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
    function getValues()
    {
        /**
         * Retourne la liste des valeurs uniques du champ fourni en parametre,
         * au format json
         */
        $this->vue->set($this->dataclass->getUniqueValuesFromParent($_REQUEST["field"], $_REQUEST["parentField"], $_REQUEST["value"]));
    }
    function search()
    {
        /**
         * Retourne la liste des especes correspondant au nom fourni,
         * au format json
         */
        $this->vue = service('Smarty');
        $this->vue->set(
            $this->dataclass->getListFromParam(
                array(
                    "nom" => $_REQUEST["nom"]
                )
            )
        );
        return $this->vue->send();
    }
    function export()
    {
        $dataSearch = $_SESSION["searchEspece"]->getParam();
        if ($_SESSION["searchEspece"]->isSearch() == 1) {
            $data = $this->dataclass->getListForExport($dataSearch);
            if (!empty($data)) {
                $this->vue->set($data);
                $this->vue->setFilename("pomet_especes.csv");
                $this->vue->send();
            } else {
                $this->message->set("Pas d'enregistrements disponibles pour l'export");
            }
        }
        return false;
    }
    function especeInitDropdownlist(array $dataSearch)
    {
        $espece = new ModelsEspece;
        $this->vue->set($espece->getUniqueValues("auteur"), "auteur");
        $fields = array(
            "phylum",
            "subphylum",
            "classe",
            "ordre",
            "famille",
            "genre"
        );
        $parentField = "";
        $parentValue = "";
        foreach ($fields as $field) {
            if (strlen($parentField) > 0) {
                /**
                 * Recherche des infos liees a un parent
                 */
                $this->vue->set($espece->getUniqueValuesFromParent($field, $parentField, $parentValue), $field);
            } else {
                $this->vue->set($espece->getUniqueValues($field), $field);
            }
            /**
             * Recherche si une selection a ete realisee sur le champ
             */
            if (strlen($dataSearch[$field]) > 0) {
                $parentField = $field;
                $parentValue = $dataSearch[$field];
            }
        }
    }
}
