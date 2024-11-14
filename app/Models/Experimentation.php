<?php

namespace App\Models;

use Ppci\Models\PpciModel;

class Experimentation extends PpciModel
{
	public function __construct()
	{
		$this->table = "experimentation";
		$this->fields = array(
			"experimentation_id" => array(
				"type" => 1,
				"key" => 1,
				"requis" => 1,
				"defaultValue" => 0
			),
			"experimentation_libelle" => array(
				"requis" => 1
			),
			"controle_enabled" => array("type" => 0, "defaultValue" => '0'),
			"speed_min" => array("type" => 1, "defaultValue" => 0),
			"speed_max" => array("type" => 1, "defaultValue" => 1000),
			"duration_min" => array("type" => 1, "defaultValue" => 0),
			"duration_max" => array("type" => 1, "defaultValue" => 120),
			"distance_min" => array("type" => 1, "defaultValue" => 0),
			"distance_max" => array("type" => 1, "defaultValue" => 10000),
			"max_allowed_distance_deviation" => array("type" => 1, "defaultValue" => 100)
		);
		parent::__construct();
	}
}
