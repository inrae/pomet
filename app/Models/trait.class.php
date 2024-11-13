<?php 
namespace App\Models;
use Ppci\Models\PpciModel;

/**
 * @author Eric Quinton
 * @copyright Copyright (c) 2015, IRSTEA / Eric Quinton
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 *  Creation 2 nov. 2015
 */

/**
 * ORM de gestion de la table trait
 *
 * @author quinton
 *
 */
class TraitTable extends PpciModel
{


    private $sql = "select trait_id, madate, station, experimentation, ordre,
			campagne_nom, masse_eau, validite
			from trait
			left outer join campagnes on (campagne_id = fk_campagne_id)
			left outer join masse_eau on (masse_eau_id = fk_masse_eau) ";

    private $sqlLire = "select trait_id, trait_id as trait_id_display, madate, engin, station, ordre, experimentation,
			fk_campagne_id as campagne_id, commentaire, conductivite::int, duree::float,
			maree::int, oxygene::float, salinite::float, profondeur::float,
			salinite_classe, temperature::float, fk_materiel_id, distance_chalutee::float,
			pos_deb_lat, pos_deb_long, pos_fin_lat, pos_fin_long, pos_deb_lat_dd, pos_deb_long_dd,
			pos_fin_lat_dd, pos_fin_long_dd, validite,
            rgfg95_deb_x, rgfg95_deb_y, rgfg95_fin_x, rgfg95_fin_y, ph, h_eau_pleine_mer,
			masse_eau, code_agence, code_masse_eau, campagne_nom, materiel_nom, saison, annee,
			salinite_libelle, p.nom, prenom, institut
			from trait
			left outer join campagnes on (campagne_id = fk_campagne_id)
			left outer join masse_eau on (masse_eau_id = fk_masse_eau)
			left outer join materiel on (materiel_id = fk_materiel_id)
			left outer join salinite on (salinite_id = salinite_classe)
			left outer join personne p on (personne_id =fk_personne)

			";

    private $order = " order by madate";

    public function __construct()
    {
        $this->table = "trait";
        $this->fields = array(
            "trait_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "madate" => array(
                "type" => 3,
                "requis" => 1
            ),
            "engin" => array(
                "type" => 0
            ),
            "station" => array(
                "type" => 0
            ),
            "ordre" => array(
                "type" => 0,
                "requis" => 1
            ),
            "experimentation" => array(
                "type" => 0
            ),
            "fk_campagne_id" => array(
                "type" => 1,
                "requis" => 1,
                "parentAttrib" => 1
            ),
            "commentaire" => array(
                "type" => 0
            ),
            "conductivite" => array(
                "type" => 1
            ),
            "duree" => array(
                "type" => 1,
                "requis" => 1
            ),
            "maree" => array(
                "type" => 1
            ),
            "oxygene" => array(
                "type" => 1
            ),
            "profondeur" => array(
                "type" => 1
            ),
            "salinite" => array(
                "type" => 1
            ),
            "salinite_classe" => array(
                "type" => 1
            ),
            "temperature" => array(
                "type" => 1
            ),
            "fk_materiel_id" => array(
                "type" => 1
            ),
            "distance_chalutee" => array(
                "type" => 1,
                "requis" => 1
            ),
            "pos_deb_lat" => array(
                "type" => 0
            ),
            "pos_deb_long" => array(
                "type" => 0
            ),
            "pos_fin_lat" => array(
                "type" => 0
            ),
            "pos_fin_long" => array(
                "type" => 0
            ),
            "pos_deb_lat_dd" => array(
                "type" => 1
            ),
            "pos_deb_long_dd" => array(
                "type" => 1
            ),
            "pos_fin_lat_dd" => array(
                "type" => 1
            ),
            "pos_fin_long_dd" => array(
                "type" => 1
            ),
            "validite" => array(
                "type" => 0,
                "defaultValue" => 1
            ),
            "rgfg95_deb_x" => array(
                "type" => 1
            ),
            "rgfg95_deb_y" => array(
                "type" => 1
            ),
            "rgfg95_fin_x" => array(
                "type" => 1
            ),
            "rgfg95_fin_y" => array(
                "type" => 1
            ),
            "h_eau_pleine_mer" => array(
                "type" => 1
            ),
            "ph" => array(
                "type" => 1
            )
        );
        $this->srid = 4326;
        parent::__construct();    }

    /**
     * Retourne le detail d'un trait
     *
     * @param int $id
     * @return array
     */
    function getDetail($id)
    {
        if ($id > 0 && is_numeric($id)) {
            $where = " where trait_id = " . $id;
            return $this->lireParam($this->sqlLire . $where);
        } else {
            return [];
        }
    }

    /**
     * Surcharge de la fonction lire pour reformater les champs en affichage
     * (non-PHPdoc)
     *
     * @see ObjetBDD::lire()
     */
    function read($id, $getDefault = false, $parentValue = 0)
    {
        if (is_numeric($id) && $id > 0) {
            return $this->getDetail($id);
        } else {
            return parent::lire($id, $getDefault, $parentValue);
        }
    }

    /**
     * Surcharge de la fonction ecrire pour mettre a jour les coordonnees geographiques
     * (non-PHPdoc)
     *
     * @see ObjetBDD::ecrire()
     */
    function write($data)
    {
        $data["validite"] == 1 ? $data["validite"] = "true" : $data["validite"] = "false";
        /*
         * Verification des controles effectues cote client
         */
        /*
         * Recuperation des donnees de l'experimentation
         */
        include_once 'modules/classes/campagne.class.php';
        include_once 'modules/classes/experimentation.class.php';
        $campagne = new Campagne($this->connection, $this->paramori);
        $dataCampagne = $campagne->lireWithMasseEau($data["fk_campagne_id"]);
        $experimentation = new Experimentation($this->connection, $this->paramori);
        $dataExp = $experimentation->lire($dataCampagne["experimentation_id"]);
        $error = false;
        if ($dataExp["controle_enabled"] == 1) {
            if ($data["duree"] > 0) {
                if ($data["duree"] > $dataExp["duration_max"] || $data["duree"] < $dataExp["duration_min"]) {
                    $error = true;
                    $this->errorData[] = array(
                        "key" => 0,
                        "message" => "La durée de pêche est hors bornes"
                    );
                }
                $vitesse = $data["distance_chalutee"] / $data["duree"];
                if ($vitesse < $dataExp["speed_min"] || $vitesse > $dataExp["speed_max"]) {
                    $error = true;
                    $this->errorData[] = array(
                        "key" => 0,
                        "message" => "La vitesse est hors bornes"
                    );
                }
            } else {
                $error = true;
                $this->errorData[] = array(
                    "key" => 0,
                    "message" => "La durée du trait n'est pas renseignée"
                );
            }
            if (strlen($data["salinite"]) == 0 && strlen($data["salinite_classe"]) == 0) {
                $error = true;
                $this->errorData[] = array(
                    "key" => 0,
                    "message" => "Ni la salinité, ni la classe de salinité ne sont renseignés"
                );
            } else if (
                strlen($data["salinite"]) > 0
                && strlen($data["salinite_classe"]) > 0
            ) {
                if (
                    ($data["salinite"] <= 5 && $data["salinite_classe"] != 1)
                    || ($data["salinite"] > 5 && $data["salinite"] <= 18 && $data["salinite_classe"] != 2)
                    || ($data["salinite"] > 18 && $data["salinite_classe"] != 3)
                ) {
                    $error = true;
                    $this->errorData[] = array(
                        "key" => 0,
                        "message" => "La salinité et la classe de salinité indiquée ne sont pas cohérentes"
                    );
                }
            }
            if ($dataCampagne["code_agence"] != "GUY") {
                if ($data["maree"] < 20 || $data["maree"] > 120) {
                    $error = true;
                    $this->errorData[] = array(
                        "key" => 0,
                        "message" => "Le coefficient de marée est hors bornes"
                    );
                }
            } else {
                if (strlen($data["h_eau_pleine_mer"]) == 0) {
                    $error = true;
                    $this->errorData[] = array(
                        "key" => 0,
                        "message" => "La hauteur d'eau de pleine mer n'a pas été renseignée"
                    );
                }
            }
            if ($data["distance_chalutee"] > $dataExp["distance_max"] || $data["distance_chalutee"] < $dataExp["distance_min"]) {
                $error = true;
                $this->errorData[] = array(
                    "key" => 0,
                    "message" => "La distance chalutée est hors bornes"
                );
            }
            /*
             * Verification des distances
             */
            $distance_gps = calcul_distance_gps($data["pos_deb_long_dd"], $data["pos_deb_lat_dd"], $data["pos_fin_long_dd"], $data["pos_fin_lat_dd"]);
            if ($distance_gps > 2000) {
                $error = true;
            }
            $dist_diff = abs($data["distance_chalutee"] - $distance_gps);
            if (($dist_diff / $data["distance_chalutee"]) > ($dataExp["max_allowed_distance_deviation"] / 100)) {
                $this->errorData[] = array(
                    "key" => 0,
                    "message" => "La distance déclarée (" . $data["distance_chalutee"] . "m) et la distance chalutée (" . $distance_gps . "m) sont trop différentes"
                );
                $error = true;
            }
        }
        /*
         * Mise en table
         */
        if (!$error) {
            $id = parent::write($data);
            /*
             * Ecriture des tables geometriques
             */
            if ($id > 0) {
                $data["trait_id"] = $id;
                if (strlen($data["pos_deb_lat_dd"]) > 0 && strlen($data["pos_deb_long_dd"]) > 0) {
                    if (strlen($data["pos_fin_lat_dd"]) > 0 && strlen($data["pos_fin_long_dd"]) > 0) {
                        $geom = new DCEligneGeom($this->connection, $this->paramori);
                    } else {
                        $geom = new DCEpointGeom($this->connection, $this->paramori);
                    }
                    if ($geom->ecrire($data) < 1) {
                        $this->errorData = array_merge($this->errorData, $geom->errorData);
                        throw new ObjetBDDException();
                    }
                }
            }
        } else {
            $id = -1;
            $this->errorData[] = array(
                "key" => 0,
                "message" => "Les contrôles effectués ne permettent pas d'enregistrer le trait - trait invalide"
            );
            throw new ObjetBDDException();
        }
        return $id;
    }

    /**
     * Surcharge de la fonction supprimer pour rajouter un controle sur les echantillons
     * et supprimer les donnees dans les tables geometriques
     *
     * @see ObjetBDD::supprimer()
     */
    function supprimer($id)
    {
        if ($id > 0 && is_numeric($id)) {
            /*
             * Recherche s'il existe des echantillons rattaches
             */
            $echantillon = new Echantillon($this->connection, $this->paramori);
            if ($echantillon->getNombreFromTrait($id) > 0) {
                $this->addMessage("Suppression du trait impossible : des échantillons lui sont rattachés");
                return -1;
            } else {
                /*
                 * Suppression des données géographiques
                 */
                $geom = new DCEligneGeom($this->connection, $this->paramori);
                $geom->supprimer($id);
                $geom = new DCEpointGeom($this->connection, $this->paramori);
                $geom->supprimer($id);
                return parent::supprimer($id);
            }
        } else {
            return -1;
        }
    }

    /**
     * Retourne la liste des traits correspondants aux criteres fournis
     *
     * @param unknown $dataSearch
     * @return tableau
     */
    function getListFromParam($dataSearch)
    {
        $where = $this->getWhere($dataSearch);
        if (strlen($where) > 0) {
            return $this->getListeParam($this->sql . $where . $this->order);
        }
    }

    /**
     * Fonction d'export des traits
     *
     * @param array $dataSearch
     * @return tableau
     */
    function getListForExport($dataSearch)
    {
        $where = $this->getWhere($dataSearch);
        if (strlen($where) > 0) {
            return $this->getListeParam($this->sqlLire . $where . " order by trait_id");
        }
    }

    /**
     * Retourne la liste des traits correspondant aux criteres fournis
     *
     * @param array $dataSearch
     * @return array
     */
    function getIdFromSearch($dataSearch)
    {
        $where = $this->getWhere($dataSearch);
        if (strlen($where) > 0) {
            $sql = "select trait_id from trait";
            return $this->getListeParam($sql . $where . " order by trait_id");
        }
    }

    /**
     * Retourne la clause where en fonction des criteres de recherche fournis
     *
     * @param array $dataSearch
     * @return string
     */
    function getWhere($dataSearch)
    {
        $where = "";
        $this->encodeData($dataSearch);
        if (is_array($dataSearch["campagne_id"]) || $dataSearch["campagne_id"] > 0) {
            $where = " where ";
            $isWhere = false;
            /*
             * forcage de la recherche par campagne, obligatoire
             */
            !$isWhere ? $isWhere = true : $where .= " and ";
            if (is_array($dataSearch["campagne_id"])) {
                $where .= " fk_campagne_id in (";
                $comma = false;
                foreach ($dataSearch["campagne_id"] as $value) {
                    !$comma ? $comma = true : $where .= ", ";
                    $where .= $value;
                }
                $where .= ") ";
            } else {
                $where .= " fk_campagne_id = " . $dataSearch["campagne_id"];
            }
            if (!$isWhere) {
                $where = "";
            }
        }
        return $where;
    }

    /**
     * Fonction retournant la liste des traits correspondants aux criteres de recherche fournis
     *
     * @param array $campagnes
     * @param array $params
     * @return array
     */
    function getListForWS($campagnes, $params)
    {
        $data = "";
        if (count($campagnes) > 0) {
            $sql = "select trait_id,
                madate::date as traitdate,
                g.pos_deb_long_dd as startlon,
                g.pos_deb_lat_dd as startlat,
                g.pos_fin_long_dd as endlon,
                g.pos_fin_lat_dd as endlat,
                maree as tidal_coef, oxygene as oxygen, salinite as salinity, temperature,
                case
                when saison = 'printemps' then 'spring'
                when saison = 'ete' then 'summer'
                when saison = 'automne' then 'automn'
                when saison = 'hiver' then 'winter'
                end as season,
                sum(nt) as number, sum(pt) as weight
                from trait
                join campagnes on (fk_campagne_id = campagne_id)
                join dce_ligne_geom g using (trait_id)
                join echantillon on (fk_trait_id = trait_id)
                join espece using (espece_id)
                ";
            $gb = " group by trait_id, madate, g.pos_deb_long_dd, g.pos_deb_lat_dd,
                g.pos_fin_long_dd, g.pos_fin_lat_dd, maree, oxygene, salinite, temperature, season";
            $and = " and ";
            $where = " where fk_campagne_id in (";
            $comma = "";
            $params = $this->encodeData($params);
            /*
             * Ajout des restrictions de campagnes
             */
            foreach ($campagnes as $campagne) {
                $where .= $comma . $campagne["campagne_id"];
                $comma = ",";
            }
            $where .= ")";
            /*
             * Traitement des parametres de recherche
             */
            if (isset($params["area"]) && is_numeric($params["area"][0]["lon"]) && is_numeric($params["area"][0]["lat"]) && is_numeric($params["area"][1]["lon"]) && is_numeric($params["area"][1]["lat"])) {
                $where .= $and . "st_contains(
                        st_setsrid(st_makebox2d(
                        st_makepoint(" . $params["area"][0]["lon"] . "," . $params["area"][0]["lat"] . "),
                        st_makepoint(" . $params["area"][1]["lon"] . "," . $params["area"][1]["lat"] . ")
                        ),4326), trait_geom)";
            }
            if (isset($params["years"])) {
                $where .= $and . "annee in (";
                $comma = "";
                foreach ($params["years"] as $annee) {
                    if (is_numeric($annee)) {
                        $where .= $comma . $annee;
                        $comma = ",";
                    }
                }
                $where .= ")";
            }
            if (isset($params["season"])) {
                switch ($params["season"]) {
                    case "spring":
                        $saison = "printemps";
                        break;
                    case "summer":
                        $saison = "ete";
                        break;
                    case "automn":
                        $saison = "automne";
                        break;
                    case "winter":
                        $saison = "hiver";
                        break;
                    default:
                }
                if ($saison) {
                    $where .= $and . "saison = '$saison'";
                }
            }
            if (isset($params["taxon"])) {
                $where .= $and . "lower(nom) = lower('" . $params["taxon"] . "')";
            }
            $traits = $this->getListeParam($sql . $where . $gb);
            if (count($traits) > 0) {
                /*
                 * Recuperation des taxons attaches a la liste
                 */
                $sql = "select distinct lower(nom) as name, nom_fr
                from echantillon
                join espece using (espece_id)
                where fk_trait_id in (";
                $comma = "";
                foreach ($traits as $row) {
                    $sql .= $comma . $row["trait_id"];
                    $comma = ",";
                }
                $sql .= ")";
                $especes = $this->getListeParam($sql);

                $data["traits"] = $traits;
                $data["taxons"] = $especes;
            }
        }
        return $data;
    }

    function getDetailForWS($trait_id)
    {
        $a_trait = array(
            "trait_id" => $trait_id
        );
        $sql = "select trait_id,
                madate::date as traitdate, duree,
                case
                when saison = 'printemps' then 'spring'
                when saison = 'ete' then 'summer'
                when saison = 'automne' then 'automn'
                when saison = 'hiver' then 'winter'
                end as season
                from trait
                join campagnes on (campagne_id = fk_campagne_id)
                where trait_id = :trait_id";
        $trait = $this->lireParamAsPrepared($sql, $a_trait);
        /*
         * Recuperation de la liste des especes rattachees
         */
        $sql = "select distinct lower(nom) as name, nom_fr, nt as number, pt as weight
                from echantillon
                join espece using (espece_id)
                where fk_trait_id = :trait_id";
        $especes = $this->getListeParamAsPrepared($sql, $a_trait);
        $trait["taxons"] = $especes;
        return $trait;
    }
}

/**
 * ORM de gestion de la table echantillon
 *
 * @author quinton
 *
 */
class Echantillon extends PpciModel
{


    private $sql = "select ech_id, espece_id, fk_trait_id,
			nt::int, pt::float, nom, nom_fr, lt_max
			from echantillon
			natural join espece
			left outer join species_size on (nom = species_consensus)";

    public function __construct()
    {
        $this->table = "echantillon";
                $this->fields = array(
            "ech_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "espece_id" => array(
                "type" => 1,
                "requis" => 1
            ),
            "fk_trait_id" => array(
                "type" => 1,
                "requis" => 1,
                "parentAttrib" => 1
            ),
            "nt" => array(
                "type" => 1
            ),
            "pt" => array(
                "type" => 1
            )
        );
        parent::__construct();    }

    /**
     * Retourne le nombre d'echantillons pour une espece donnee
     *
     * @param int $espece_id
     * @return int
     */
    function getNbFromEspece($espece_id)
    {
        if ($espece_id > 0 && is_numeric($espece_id)) {
            $sql = "select count(*) as nombre from " . $this->table . " where espece_id = " . $espece_id;
            $data = $this->lireParam($sql);
            return ($data["nombre"]);
        }
    }

    /**
     * Retourne la liste des echantillons pour un trait
     *
     * @param int $trait_id
     * @return tableau
     */
    function getListeFromTrait($trait_id)
    {
        if ($trait_id > 0 && is_numeric($trait_id)) {
            $where = " where fk_trait_id = " . $trait_id;
            $order = " order by ech_id ";
            return $this->getListeParam($this->sql . $where . $order);
        }
    }

    /**
     * Surcharge de la fonction lire pour reformater les champs en affichage
     * (non-PHPdoc)
     *
     * @see ObjetBDD::lire()
     */
    function read($id, $getDefault = false, $parentValue = 0)
    {
        if (is_numeric($id) && $id > 0) {
            return $this->getDetail($id);
        } else {
            return parent::lire($id, $getDefault, $parentValue);
        }
    }

    /**
     * Recalcule le nombre total et la masse totale pour l'échantillon, et met a jour
     * l'information le cas echeant
     *
     * @param int $id
     */
    function updateNbTotal($id)
    {
        if ($id > 0 && is_numeric($id)) {
            $individu = new Individu($this->connection, $this->paramori);
            $data = $this->lire($id);
            $updateRequired = false;
            $dataIndiv = $individu->getTotalFromEchantillon($id);
            if ($dataIndiv["nombre"] > $data["nt"]) {
                $data["nt"] = $dataIndiv["nombre"];
                $updateRequired = true;
            }
            if ($dataIndiv["masse"] > $data["pt"]) {
                $data["pt"] = $dataIndiv["masse"];
                $updateRequired = true;
            }
            if ($updateRequired) {
                parent::write($data);
            }
        }
    }

    /**
     * Surcharge de la fonction supprimer pour enlever les poissons rattaches
     *
     * @see ObjetBDD::supprimer()
     */
    function supprimer($id)
    {
        if ($id > 0 && is_numeric($id)) {
            /*
             * Suppression des individus rattaches
             */
            $individu = new Individu($this->connection, $this->paramori);
            $individu->supprimerChamp($id, "fk_ech_id");
            return parent::supprimer($id);
        }
    }

    /**
     * Retourne le nombre d'echantillons rattaches a un trait
     *
     * @param int $traitId
     * @return int
     */
    function getNombreFromTrait($traitId)
    {
        if ($traitId > 0 && is_numeric($traitId)) {
            $sql = "select count(*) as nombre from echantillon
					where fk_trait_id = " . $traitId;
            $data = $this->lireParam($sql);
            return ($data["nombre"]);
        }
    }

    /**
     * Retourne le detail d'un echantillon
     *
     * @param int $id
     * @return array
     */
    function getDetail($id)
    {
        if ($id > 0 && is_numeric($id)) {
            $where = " where ech_id = " . $id;
            return $this->lireParam($this->sql . $where);
        } else {
            return [];
        }
    }

    /**
     * Prepare la liste des echantillons a exporter, qui correspondent aux parametres de recherche
     * des traits fournis dans le tableau
     *
     * @param array $dataSearch
     * @return array
     */
    function getListForExport(array $dataSearch)
    {
        $trait = new TraitTable($this->connection, $this->paramori);
        $traits = $trait->getIdFromSearch($dataSearch);
        if (count($traits) > 0) {
            $sql = "select fk_trait_id as trait_id, ech_id, nom, code_sandre, nt, pt
					from echantillon
					join espece using (espece_id)";
            $where = " where fk_trait_id in (";
            $virgule = "";
            foreach ($traits as $value) {
                $where .= $virgule . $value["trait_id"];
                $virgule = ",";
            }
            $where .= ")";
            $order = " order by fk_trait_id";
            return $this->getListeParam($sql . $where . $order);
        }
    }
}

class Individu extends PpciModel
{


    private $sql = "select ind_id, fk_ech_id,
			longueur::float, poids::float
			from individu";

    public function __construct()
    {
        $this->table = "individu";
                $this->fields = array(
            "ind_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "fk_ech_id" => array(
                "type" => 1,
                "requis" => 1,
                "parentAttrib" => 1
            ),
            "longueur" => array(
                "type" => 1
            ),
            "poids" => array(
                "type" => 1
            )
        );
        parent::__construct();    }

    /**
     * Surcharge de la fonction lire pour reformater les champs en affichage
     * (non-PHPdoc)
     *
     * @see ObjetBDD::lire()
     */
    function read($id, $getDefault = false, $parentValue = 0)
    {
        if (is_numeric($id) && $id > 0) {
            return $this->getDetail($id);
        } else {
            return parent::lire($id, $getDefault, $parentValue);
        }
    }

    /**
     * Retourne le detail d'un individu
     *
     * @param int $id
     * @return array
     */
    function getDetail($id)
    {
        if ($id > 0 && is_numeric($id)) {
            $where = " where ind_id = " . $id;
            return $this->lireParam($this->sql . $where);
        }
    }

    /**
     * Retourne la liste des individus rattaches a un echantillon
     *
     * @param unknown $id
     * @return tableau
     */
    function getListFromEchantillon($id)
    {
        if ($id > 0 && is_numeric($id)) {
            $where = " where fk_ech_id = " . $id;
            $order = " order by ind_id";
            return $this->getListeParam($this->sql . $where . $order);
        }
    }

    /**
     * Retourne le nombre de poissons, et leur masse totale,
     * pour un echantillon
     *
     * @param int $id
     *            : code de l'echantillon
     * @return array["nombre", "masse"]
     */
    function getTotalFromEchantillon($id)
    {
        if ($id > 0 && is_numeric($id)) {
            $sql = "select count(*) as nombre, sum(poids) as masse
					from individu
					where fk_ech_id = " . $id;
            return $this->lireParam($sql);
        }
    }

    /**
     * Retourne la liste des individus correspondant a la liste des traits determinee
     * par les parametres de recherche fournis
     *
     * @param array $dataSearch
     * @return tableau
     */
    function getListForExport(array $dataSearch)
    {
        $trait = new TraitTable($this->connection, $this->paramori);
        $traits = $trait->getIdFromSearch($dataSearch);
        if (count($traits) > 0) {
            $sql = "select fk_trait_id as trait_id, fk_ech_id as ech_id, ind_id, nom, code_sandre, longueur, poids
					 from individu
					join echantillon on (fk_ech_id = ech_id)
					join espece using (espece_id)";
            $where = " where fk_trait_id in (";
            $virgule = "";
            foreach ($traits as $value) {
                $where .= $virgule . $value["trait_id"];
                $virgule = ",";
            }
            $where .= ")";
            $order = " order by fk_trait_id";
            return $this->getListeParam($sql . $where . $order);
        }
    }
}

/**
 * ORM de gestion de la table des classes de salinite
 *
 * @author quinton
 *
 */
class Salinite extends PpciModel
{

    public function __construct()
    {
        $this->table = "salinite";
                $this->fields = array(
            "salinite_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1,
                "defaultValue" => 0
            ),
            "salinite_libelle" => array(
                "type" => 0,
                "requis" => 1
            )
        );
        parent::__construct();    }
}

/**
 * ORM de gestion de la table dce_ligne_geom
 *
 * @author quinton
 *
 */
class DCEligneGeom extends PpciModel
{

    public function __construct()
    {
        $this->table = "dce_ligne_geom";
        $this->useAutoIncrement = false;
        $this->fields = array(
            "trait_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1
            ),
            "pos_deb_long_dd" => array(
                "type" => 1,
                "requis" => 1
            ),
            "pos_deb_lat_dd" => array(
                "type" => 1,
                "requis" => 1
            ),
            "pos_fin_long_dd" => array(
                "type" => 1,
                "requis" => 1
            ),
            "pos_fin_lat_dd" => array(
                "type" => 1,
                "requis" => 1
            ),
            "trait_geom" => array(
                "type" => 4
            )
        );

        $param["srid"] = 4326;
        parent::__construct();    }

    /**
     * Surcharge de la fonction ecrire pour generer le point geographique
     * (non-PHPdoc)
     *
     * @see ObjetBDD::ecrire()
     */
    function write($data)
    {
        /*
         * Preparation de trait_geom
         */
        $id = parent::write($data);
        if ($id > 0) {
            $sql = "update dce_ligne_geom set trait_geom = "
                . "st_setsrid(st_makeline(st_makepoint(" . $data["pos_deb_long_dd"] . "," . $data["pos_deb_lat_dd"] . "),st_makepoint(" . $data["pos_fin_long_dd"] . "," . $data["pos_fin_lat_dd"] . ")),4326)
            where trait_id = " . $id;
        }
        $this->executeSQL($sql);
        return $id;
    }
}

/**
 * ORM de gestion de la table dce_point_geom
 *
 * @author quinton
 *
 */
class DCEpointGeom extends PpciModel
{

    public function __construct()
    {
        $this->table = "dce_point_geom";
        $this->useAutoIncrement = false;
        $this->fields = array(
            "trait_id" => array(
                "type" => 1,
                "key" => 1,
                "requis" => 1
            ),
            "pos_deb_long_dd" => array(
                "type" => 1,
                "requis" => 1
            ),
            "pos_deb_lat_dd" => array(
                "type" => 1,
                "requis" => 1
            ),
            "trait_geom" => array(
                "type" => 4
            )
        );
        $param["srid"] = 4326;
        parent::__construct();    }

    /**
     * Surcharge de la fonction ecrire pour generer le point geographique
     * (non-PHPdoc)
     *
     * @see ObjetBDD::ecrire()
     */
    function write($data)
    {
        /*
         * Preparation de trait_geom
         */
        /*
         * Preparation de trait_geom
         */
        $id = parent::write($data);
        if ($id > 0) {
            $sql = "update dce_ligne_geom set trait_geom = " . "st_setsrid(st_makepoint(" . $data["pos_deb_long_dd"] . "," . $data["pos_deb_lat_dd"] . "),4326)";
        }
        $this->executeSQL($sql);
        return $id;
    }
}
