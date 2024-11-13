<?php 
namespace App\Models;
use Ppci\Models\PpciModel;

/**
 * Created : 19 févr. 2018
 * Creator : quinton
 * Encoding : UTF-8
 * Copyright 2018 - All rights reserved
 */
class GpsException extends Exception
{
};

class Tracegps extends PpciModel
{

  private $traces = array();

  private $dateMin, $dateMax;

  private $pointCounter;

  private $dom;

  public public function __construct()
  {
    $this->table = "tracegps";
    $this->useAutoIncrement = false;
    $this->srid = 4326;
    $this->fields = array(
      "trait_id" => array(
        "type" => 1,
        "key" => 1,
        "requis" => 1,
      ),
      "trace_start" => array(
        "type" => 3,
        "requis" => 1,
      ),
      "trace_end" => array(
        "type" => 3,
        "requis" => 1,
      ), /*
         * ,
         * "ligne_geom" => array(
         * "type" => 0,
         * "requis" => 1
         * )
         */
    );
    parent::__construct();  }

  /**
   * Lit le fichier GPX fourni, et assigne le tableau $traces
   *
   * @param string $filename
   * @return array $traces
   * @throws GpsException
   */
  public function parseGpx($filename)
  {
    if (strlen($filename) > 0) {
      if (filesize($filename) > 10) {
        $this->traces = array();
        $this->dateMin = "";
        $this->dateMax = "";
        $numTrace = 0;
        $this->dom = new DOMDocument();
        if (!$this->dom->load($filename)) {
          throw new GpsException("Impossible de lire le fichier $filename");
        }
        $traces = $this->dom->getElementsByTagName("trk");
        foreach ($traces as $trace) {
          $this->dateMin = "";
          $this->dateMax = "";
          $this->pointCounter = 0;
          if ($trace->hasChildNodes()) {
            foreach ($trace->childNodes as $traceElem) {
              /**
               * Recuperation du nom et du numero de la trace
               */
              switch ($traceElem->nodeName) {
                case "name":
                  $this->traces[$numTrace]["name"] = $traceElem->nodeValue;
                  break;
                case "number":
                  $this->traces[$numTrace]["number"] = $traceElem->nodeValue;
                  break;
                case "trkseg":
                  $points = $this->extractPoints($traceElem->childNodes);
                  foreach ($points as $point) {
                    $this->traces[$numTrace]["points"][] = $point;
                  }
                  break;
              }
            }
          }
          /**
           * Affectation d'un numero de trace si inexistant
           */
          if (!isset($this->traces[$numTrace]["number"])) {
            $this->traces[$numTrace]["number"] = $numTrace;
          }
          /**
           * Ajout des dates de debut et de fin de la trace
           */
          $this->traces[$numTrace]["start"] = $this->dateMin;
          $this->traces[$numTrace]["end"] = $this->dateMax;
          $numTrace++;
        }
      } else {
        throw new GpsException("File is empty or too small");
      }
    } else {
      throw new GpsException("Filename is empty");
    }
    return $this->traces;
  }

  public function extractPoints($segment)
  {
    $points = array();
    foreach ($segment as $segelem) {
      $point = array();
      if ($segelem->nodeName == "trkpt") {
        /**
         * Extraction de la longitude et de la latitude
         */
        foreach ($segelem->attributes as $attribut) {
          $point[$attribut->name] = $attribut->value;
        }
        /**
         * recuperation de l'heure
         */
        foreach ($segelem->childNodes as $elem) {
          if ($elem->nodeName == "time") {
            if (strlen($this->dateMin) == 0) {
              $this->dateMin = $elem->nodeValue;
            }
            $this->dateMax = $elem->nodeValue;
          }
        }
        $points[$this->pointCounter()] = $point;
      }
    }
    return $points;
  }

  private function pointCounter()
  {
    $this->pointCounter++;
    return $this->pointCounter - 1;
  }

  /**
   * Function generateTrace : écriture de  la trace dans la table
   * à partir des numeros des traces indiques
   *
   * @param mixed $traces
   * @param mixed $tracenum
   * @param mixed $trait_id
   *
   * @return mixed
   */
  public function generateTrace($traces, $tracenum, $trait_id)
  {
    if (!is_array($tracenum)) {
      $num = array(
        $tracenum,
      );
    } else {
      $num = $tracenum;
    }
    $this->traces = $traces;
    $data = array();
    /**
     * Recuperation de tous les points de la trace
     */
    $line = "st_makeline(array[";
    $comma = "";
    foreach ($traces as $trace) {
      /**
       * Recuperation des dates
       */
      if (in_array($trace["number"], $num)) {

        if (!isset($data["trace_start"])) {
          $data["trace_start"] = $trace["start"];
        }
        $data["trace_end"] = $trace["end"];
        foreach ($trace["points"] as $point) {
          $line .= $comma .
            "st_setsrid(st_makepoint(" . $point["lon"] . "," . $point["lat"] . "),4326)";
          $comma = ",";
        }
      }
    }
    $line .= "])";
    $data["ligne_geom"] = $line;
    $data["trait_id"] = $trait_id;
    $data = $this->encodeData($data);
    $this->delete($data["trait_id"]);
    $sql = "insert into tracegps (trait_id, trace_start, trace_end, ligne_geom) values (" .
      $data["trait_id"] . ", '" . $data["trace_start"] . "','" . $data["trace_end"] . "'," .
      $data["ligne_geom"] . ")";
    return $this->executeSQL($sql);
  }

  public function getTrace($trait_id)
  {
    if (is_numeric($trait_id)) {
      $data = $this->lire($trait_id);
      if ($data["trait_id"] > 0) {
        $value = "[";
        $sql = "select st_astext(ligne_geom) as ligne_geom from tracegps where trait_id = :trait_id";
        $res = $this->lireParamAsPrepared($sql, array(
          "trait_id" => $trait_id,
        ));
        if (strlen($res["ligne_geom"]) > 10) {
          $ligne = substr($res["ligne_geom"], 11, strlen($res["ligne_geom"]) - 12);
          $points = explode(",", $ligne);
          $comma = "";
          foreach ($points as $point) {
            $pt = explode(" ", $point);
            $value .= $comma . "[" . $pt[0] . "," . $pt[1] . "]";
            $comma = ",";
          }
        }
        $value .= "]";
        $data["ligne_geom"] = $value;
        return $data;
      }
    }
  }
  /**
   * Compute the length of GPS trace
   *
   * @param integer $trait_id
   * @return float
   */
  public function calculLength(int $trait_id): float
  {
    $length = 0;
    if ($trait_id > 0) {
      $param = array("trait_id" => $trait_id);
      /**
       * get the code_agence
       */
      $sql = "select code_agence
                    from trait
                    join campagnes on (fk_campagne_id = campagne_id)
                    join masse_eau on (fk_masse_eau = masse_eau_id)
                    where trait_id = :trait_id";
      $data = $this->lireParamAsPrepared($sql, $param);
      $data["code_agence"] == "GUY" ? $epsg = 2972 : $epsg = 2154;
      $sql = "select st_length (st_transform(ligne_geom, $epsg)) as length
                    from tracegps
                    where trait_id = :trait_id";
      $data = $this->lireParamAsPrepared($sql, $param);
      if ($data["length"] > 0) {
        $length = $data["length"];
      }
    }
    return $length;
  }
  /**
   * Importe une trace fournie sous forme de fichier CSV
   * Le fichier CSV doit contenir les champs time, lat, lon
   * @param int trait_id
   * @param string $filename
   * @param string $separator
   * @return mixed
   */
  public function importCsv(int $trait_id, string $filename, string $separator = ",")
  {
    if (!file_exists($filename)) {
      throw new GpsException("Le fichier n'a pas été téléchargé");
    }
    $handle = fopen($filename, 'r');
    if (!$handle) {
      throw new GpsException("Le fichier n'a pas pu être ouvert par le serveur");
    }
    /**
     * Get the first line and compute the number of columns
     */
    if ($separator == "tab") {
      $separator = "\t";
    }
    $columns = array("time", "lat", "lon");
    $i = 0;
    $header = array();
    $firstline = fgetcsv($handle, 1000, $separator);
    foreach ($firstline as $columnName) {
      if (in_array($columnName, $columns)) {
        $header["$columnName"] = $i;
      }
      $i++;
    }
    if (count($header) != 3) {
      throw new GpsException("Le fichier ne contient pas les colonnes nécessaires pour l'importation : time, lat, lon");
    }
    $data = array();
    $line = "st_makeline(array[";
    $comma = "";
    while (($point = fgetcsv($handle, 1000, $separator)) !== FALSE) {
      if ($point != null) {
        if (!isset($data["trace_start"])) {
          $data["trace_start"] = $point[$header["time"]];
        }
        $data["trace_end"] = $point[$header["time"]];
        $line .= $comma .
          "st_setsrid(st_makepoint(" . $point[$header["lon"]] . "," . $point[$header["lat"]] . "),4326)";
        $comma = ",";
      }
    }
    fclose($handle);
    $line .= "])";
    $data["ligne_geom"] = $line;
    $data["trait_id"] = $trait_id;
    $data = $this->encodeData($data);
    $this->delete($data["trait_id"]);
    $sql = "insert into tracegps (trait_id, trace_start, trace_end, ligne_geom) values (" .
      $data["trait_id"] . ", '" . $data["trace_start"] . "','" . $data["trace_end"] . "'," .
      $data["ligne_geom"] . ")";
    return $this->executeSQL($sql);
  }
}
