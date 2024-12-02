<?php

namespace App\Config;

use Ppci\Config\RightsPpci;

/**
 * List of all rights required by modules
 */
class Rights extends RightsPpci
{
    protected array $rights = [
        "dbstructureHtml" => ["param"],
        "dbstructureGacl" => ["admin"],
        "dbstructureLatex" => ["param"],
        "dbstructureSchema" => ["param"],
        "especeList" => ["consult"],
        "especeDisplay" => ["consult"],
        "especeChange" => ["param"],
        "especeWrite" => ["param"],
        "especeDelete" => ["param"],
        "especeGetValues" => ["consult"],
        "especeSearch" => ["consult", "manage", "saisie"],
        "especeExport" => ["consult", "manage", "saisie"],
        "materielList" => ["consult"],
        "materielChange" => ["param"],
        "materielWrite" => ["param"],
        "materielDelete" => ["param"],
        "materielExport" => ["consult"],
        "experimentationList" => ["consult"],
        "experimentationChange" => ["param"],
        "experimentationWrite" => ["param"],
        "experimentationDelete" => ["param"],
        "campagneList" => ["consult"],
        "campagneChange" => ["param"],
        "campagneWrite" => ["param"],
        "campagneDelete" => ["param"],
        "campagneDuplicate" => ["param"],
        "personneList" => ["consult"],
        "personneChange" => ["param"],
        "personneWrite" => ["param"],
        "personneDelete" => ["param"],
        "traitList" => ["consult", "saisie"],
        "traitDisplay" => ["consult", "saisie"],
        "traitChange" => ["saisie", "manage"],
        "traitWrite" => ["saisie", "manage"],
        "traitDelete" => ["saisie", "manage"],
        "traitExport" => ["consult", "manage", "saisie"],
        "campagneSearchTrait" => ["consult", "saisie", "manage"],
        "echantillonChange" => ["saisie", "manage"],
        "echantillonWrite" => ["saisie", "manage"],
        "echantillonDelete" => ["saisie", "manage"],
        "echantillonExport" => ["consult", "manage", "saisie"],
        "individuWrite" => ["saisie", "manage"],
        "individuDelete" => ["saisie", "manage"],
        "individuExport" => ["consult", "manage", "saisie"],
        "importgpxDisplay" => ["saisie", "manage"],
        "importgpxSelectfile" => ["saisie", "manage"],
        "importgpxExec" => ["saisie", "manage"],
        "importgpscsvDisplay" => ["saisie", "manage"],
        "importgpscsvExec" => ["saisie", "manage"],
        "traitShapeSelect" => ["param"],
        "traitShapeExec" => ["param"]
    ];
}
