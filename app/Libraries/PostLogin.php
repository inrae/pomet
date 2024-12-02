<?php

namespace App\Libraries;

use App\Models\Campagne;
use Ppci\Libraries\PpciLibrary;

class PostLogin extends PpciLibrary
{
    static function index()
    {
        $campagne = new Campagne;
        $campagnes = $campagne->getListFromUser($_SESSION["login"]);
        if (count($campagnes) > 0) {
            $_SESSION["userRights"]["saisie"] = 1;
        }
    }
}
