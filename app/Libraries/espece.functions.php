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
 *  Creation 15 oct. 2015
 */

/**
 * Transmet la liste des valeurs uniques des differents attributs des especes a Smarty
 *
 * @param Espece $espece
 */
function especeInitDropdownlist(Espece $espece, array $dataSearch)
{
    global $this->vue;
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
            /*
             * Recherche des infos liees a un parent
             */
            $this->vue->set($espece->getUniqueValuesFromParent($field, $parentField, $parentValue), $field);
        } else {
            $this->vue->set($espece->getUniqueValues($field), $field);
        }
        /*
         * Recherche si une selection a ete realisee sur le champ
         */
        if (strlen($dataSearch[$field]) > 0) {
            $parentField = $field;
            $parentValue = $dataSearch[$field];
        }
    }
}

?>