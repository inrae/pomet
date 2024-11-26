<?php

namespace App\Controllers;

use \Ppci\Controllers\PpciController;
use App\Libraries\ImportGpx as LibrariesImportGpx;

class ImportGpx extends PpciController
{
    protected $lib;
    function __construct()
    {
        $this->lib = new LibrariesImportGpx();
    }
    function display()
    {
        return $this->lib->display();
    }
    function selectfile()
    {
        if (! $this->lib->selectfile()) {
            return $this->display();
        }
    }
    function exec()
    {
        if ( $this->lib->exec()) {
            $trait = new Traits;
            return $trait->list();
        } else {
            return $this->display();
        }
    }
}
