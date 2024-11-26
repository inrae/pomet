<?php

namespace App\Controllers;

use \Ppci\Controllers\PpciController;
use App\Libraries\Espece as LibrariesEspece;

class Espece extends PpciController
{
    protected $lib;
    function __construct()
    {
        $this->lib = new LibrariesEspece();
    }
    function list()
    {
        return $this->lib->list();
    }
    function display()
    {
        return $this->lib->display();
    }
    function change()
    {
        return $this->lib->change();
    }
    function write()
    {
        if ($this->lib->write()) {
            return $this->display();
        } else {
            return $this->change();
        }
    }
    function delete()
    {
        if ($this->lib->delete()) {
            return $this->list();
        } else {
            return $this->change();
        }
    }
    function getValues()
    {
        return $this->lib->getValues();
    }
    function search()
    {
        return $this->lib->search();
    }
    function export()
    {
        return $this->lib->export();
    }
}
