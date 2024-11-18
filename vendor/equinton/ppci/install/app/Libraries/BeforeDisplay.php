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

namespace App\Libraries;

use Ppci\Libraries\PpciLibrary;
use Ppci\Libraries\Views\SmartyPpci;

class BeforeDisplay extends PpciLibrary
{
    static function index() {}
    static function setGeneric(SmartyPpci $this->vue) {
        
    }
}
