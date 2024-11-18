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
 * @author Eric Quinton
 * @copyright Copyright (c) 2015, IRSTEA / Eric Quinton
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 *  Creation 16 oct. 2015
 */
include_once 'modules/classes/materiel.class.php';
$this->dataclass = new Materiel;
$keyName = "materiel_id";
$this->id = $_REQUEST[$keyName];


    function list(){
$this->vue=service('Smarty');
		/*
		 * Display the list of all records of the table
		 */
		$this->vue->set($this->dataclass->getListe(3), "data");
        $this->vue->set("parametre/materielList.tpl", "corps");
        }
    function change(){
$this->vue=service('Smarty');
		/*
		 * open the form to modify the record
		 * If is a new record, generate a new record with default value :
		 * $_REQUEST["idParent"] contains the identifiant of the parent record
		 */
		require_once 'modules/classes/experimentation.class.php';
        $experimentation = new Experimentation;
        $this->vue->set($experimentation->getListe(2), "experimentation");
        $this->vue->set($this->dataclass->getUniqueValues("materiel_type"), "materiel_type");
        $this->dataRead( $this->id, "parametre/materielChange.tpl");
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
		 * write record in database
		 */
		$this->id = dataWrite($this->dataclass, $_REQUEST);
        if ($this->id > 0) {
            $_REQUEST[$keyName] = $this->id;
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
        $data = $this->dataclass->getListForExport();
        if (count($data) > 0) {
            $this->vue->set($data);
            $this->vue->setFilename( "pomet_materiels.csv");
        } else {
            $module_coderetour = -1;
            $this->message->set("Pas de matériel à exporter");
            unset ($this->vue);
        }
        }
}

?>