<?php

namespace App\Controllers;

use \Ppci\Controllers\PpciController;
use App\Libraries\Echantillon as LibrariesEchantillon;
use App\Libraries\Traits;

class Echantillon extends PpciController
{
    protected $lib;
    function __construct()
    {
        $this->lib = new LibrariesEchantillon();
    }
    function change()
    {
        return $this->lib->change();
    }
    function write()
    {
        $this->lib->write();
        return $this->change();
    }
    function delete()
    {
        if ($this->lib->delete()) {
            $trait = new Traits;
            return $trait->display();
        } else {
            return $this->lib->change();
        }
    }
    function export()
    {
        return $this->lib->export();
    }
}
