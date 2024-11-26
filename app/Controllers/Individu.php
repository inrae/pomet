<?php

namespace App\Controllers;


use \Ppci\Controllers\PpciController;
use App\Libraries\Individu as LibrariesIndividu;

class Individu extends PpciController
{
    protected $lib;
    function __construct()
    {
        $this->lib = new LibrariesIndividu();
    }
    function write()
    {
        $this->lib->write();
        $echantillon = new Echantillon;
        return $echantillon->change();
    }
    function delete()
    {
        $this->lib->delete();
        $echantillon = new Echantillon;
        return $echantillon->change();
    }
    function export()
    {
        if (! $this->lib->export()) {
            $trait = new Traits;
            return $trait->list();
        }
    }
}
