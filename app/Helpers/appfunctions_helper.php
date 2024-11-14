<?php

use App\Libraries\DefaultLibrary;
/**
 * Fonction de calcul de la longueur entre deux points GPS
 *
 * @param float $x1
 *        	: longitude départ, en degrés décimaux
 * @param float $y1
 *        	: latitude départ, en degrés décimaux
 * @param float $x2
 *        	: longitude arrivée, en degrés décimaux
 * @param float $y2
 *        	: latitude arrivée, en degrés décimaux
 * @return float : longueur entre les deux points, en mètres
 */
function calcul_distance_gps($x1, $y1, $x2, $y2) {
	$fields = array (
			"x1",
			"x2",
			"y1",
			"y2"
	);
	$numeric = true;
	foreach ( $fields as $field ) {
		if (! is_numeric ( $$field )) {
			$numeric = false;
		}
	}
	if ($numeric) {
		$diametre_terre = 6378137;
		/*
		 * Transformation en radian
		 */
		$rad = array ();
		foreach ( $fields as $field ) {
			$rad [$field] = deg2rad ( $$field );
		}
		/*
		 * Calcul de l'écart de longitude
		 */
		$dlong = $rad ["x2"] - $rad ["x1"];
		$sab = acos ( sin ( $rad ["y1"] ) * sin ( $rad ["y2"] ) + cos ( $rad ["y1"] ) * cos ( $rad ["y2"] ) * cos ( $dlong ) );
		return $sab * $diametre_terre;
	}
}