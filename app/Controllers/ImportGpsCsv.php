<?php

namespace App\Controllers;

use \Ppci\Controllers\PpciController;
use App\Libraries\ImportGpsCsv as LibrariesImportGpsCsv;
use App\Libraries\Traits;

class ImportGpsCsv extends PpciController
{
    protected $lib;
    function __construct()
    {
        $this->lib = new LibrariesImportGpsCsv();
    }
    function display()
    {
        return $this->lib->display();
    }
    function exec()
    {
        if( $this->lib->exec()) {
            $trait = new Traits;
            return $trait->list();
        } else {
            return $this->display();
        }
    }
}
