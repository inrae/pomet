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

/**
 * Fonction permettant d'initialiser les valeurs pour les zones select
 * 
 * @param Campagne $campagne
 */
function campagneInitSelectList(Campagne $campagne)
{
    global $this->vue;
    $this->vue->set($campagne->getSaisons(), "saisons");
    $this->vue->set($campagne->getAnnees(), "annees");
}

?>