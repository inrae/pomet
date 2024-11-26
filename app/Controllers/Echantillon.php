<?php

namespace App\Controllers;

use \Ppci\Controllers\PpciController;
use App\Libraries\Echantillon as LibrariesEchantillon;

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
        $this->lib->delete();
        return $this->change();
    }
    function export()
    {
        return $this->lib->export();
    }
}
