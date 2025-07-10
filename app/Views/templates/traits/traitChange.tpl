<script src="display/node_modules/proj4/dist/proj4.js"></script>
<script>
	$(document).ready(function () {
		var border_false = "1px solid #CC0000";
		var border_true = $("#station").css("border");
		var longueurCalculee = 0;
		var hasfocus = "";
		var isGuyane = 0;
		if ("{$campagne[0].code_agence}" == "GUY") {
			isGuyane = 1;
		}
		proj4.defs("EPSG:2972", "+proj=utm +zone=22 +ellps=GRS80 +towgs84=2,2,-2,0,0,0,0 +units=m +no_defs");
		proj4.defs("EPSG:2154","+proj=lcc +lat_0=46.5 +lon_0=3 +lat_1=49 +lat_2=44 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs +type=crs");

		$(":input").focus(function () {
			hasfocus = $(this).attr("name");
		});
		//var earth_radius = 6378137;  // one of WGS84 earth radius
		var earth_radius = 6389125.541;
		function convertGPStoDD(valeur) {
			var parts = valeur.trim().split(/[^\d]+/);
			var dd = parseFloat(parts[0])
				+ parseFloat((parts[1] + "." + parts[2]) / 60);
			var lastChar = valeur.substr(-1).toUpperCase();
			dd = Math.round(dd * 1000000) / 1000000;
			if (lastChar == "S" || lastChar == "W" || lastChar == "O") {
				dd *= -1;
			}
			;
			return dd;
		}

		function calculLongueurTrait() {
			/*
			 * Calcul de la longueur du trait a partir des coordonnees gps
			 */
			//create sphere to measure on
			var longueur = 0;
			//var wgs84sphere = new ol.Sphere(earth_radius);
			var long_deb = $('#pos_deb_long_dd').val();
			var lat_deb = $('#pos_deb_lat_dd').val();
			var long_fin = $('#pos_fin_long_dd').val();
			var lat_fin = $('#pos_fin_lat_dd').val();
			if (long_deb.length > 0 && lat_deb.length > 0 && long_fin.length > 0 && lat_fin.length > 0) {
				// get distance on sphere
				/*longueur = map.distance([long_deb, lat_deb], [long_fin, lat_fin]);
				longueur = parseInt(longueur);
				longueurCalculee = longueur;*/
				if (isGuyane == 1) {
					epsg = 'EPSG:2172';
				} else {
					epsg = 'EPSG:2154';
				}
				var pointA = proj4( 'EPSG:4326', epsg, [parseFloat(long_deb), parseFloat(lat_deb)]);
				var pointB = proj4( 'EPSG:4326', epsg, [parseFloat(long_fin), parseFloat(lat_fin)]);
				longueur = parseInt(Math.sqrt(Math.pow(Math.abs(pointA[0]-pointB[0]),2)+Math.pow(Math.abs(pointA[1]- pointB[1]),2)));
			}
			/*
			 * Mise a jour de la carte
			 */
			if (long_deb.length > 0 && lat_deb.length > 0) {
				setPosition(0, lat_deb, long_deb);
			}
			if (long_fin.length > 0 && lat_fin.length > 0) {
				setPosition(1, lat_fin, long_fin);
			}
			$("#longueur_trait_gps").val(longueur);
			validDistance();
		}
		function calculVitesse() {
			/*
			 * calcule la vitesse du trait
			 */
			var errorVitesse = false;
			var chalut = parseInt($("#distance_chalutee").val());
			var duree = parseInt($("#duree").val());
			if (chalut > 0 && duree > 0) {
				var vitesse = parseInt(chalut / duree);
				$("#vitesse").val(vitesse);
				var speedMax = "{$experimentation.speed_max}";
				var speedMin = "{$experimentation.speed_min}";
				if ("{$experimentation.controle_enabled}" == 1) {
					if (vitesse > speedMax || vitesse < speedMin) {
						$("#vitesse").css("border", border_false);
						errorVitesse = true;
					} else {
						$("#vitesse").css("border", border_true);
					}
				}
				activeForm(errorVitesse);
			}
			return errorVitesse;
		}

		function validSalinite() {
			/*
			 * Valide la salinite
			 */
			var errorSal = false;
			var salinite = $("#salinite").val();
			var salinite_classe = $("#salinite_classe option:selected").val();
			if (salinite.length == 0) {
				if (salinite_classe.length == 0) {
					errorSal = true;
				}
			}
			if (salinite.length > 0 && salinite_classe.length > 0) {
				/*
				 * Verification de la coherence entre la salinite declaree et la classe correspondante
				 */
				var iSalinite = parseFloat(salinite);
				var iClasse = parseInt(salinite_classe);
				if (iSalinite <= 5 && iClasse != 1)
					errorSal = true;
				if (iSalinite > 5 && iSalinite <= 18 && iClasse != 2)
					errorSal = true;
				if (iSalinite > 18 && iClasse != 3)
					errorSal = true;
			}

			if (errorSal == true) {
				/*
				 * Mise en place de bordures
				 */
				$("#salinite").css("border", border_false);
				$("#salinite_classe").css("border", border_false);
			} else {
				$("#salinite").css("border", border_true);
				$("#salinite_classe").css("border", border_true);
			}
			activeForm(errorSal);
			return errorSal;
		}
		function validMaree() {
			/*
			 * Verification du coefficient de maree
			 */

			var errorMaree = false;
			var mareeField = $("#maree").val();
			if (mareeField.length == 0) {
				errorMaree = true;
			} else {
				if (isGuyane == 1) {

				} else {
					var maree = parseInt(mareeField);
					if (maree < 20 || maree > 120) {
						errorMaree = true;
					}
					if (errorMaree == true) {
						$("#maree").css("border", border_false);
					} else {
						$("#maree").css("border", border_true);
					}
				}
			}
			activeForm(errorMaree);
			return errorMaree;
		}

		function validDuree() {
			if ("{$experimentation.controle_enabled}" != 1) {
				return false;
			} else {
				/*
				 * Verifie les anomalies de temps
				 */
				var error = false;
				var dureeField = $("#duree").val();
				if (dureeField.length == 0) {
					error = true;
				}
				var duree = parseInt(dureeField);
				var durationMin = "{$experimentation.duration_min}";
				var durationMax = "{$experimentation.duration_max}";
				if (duree < durationMin || duree > durationMax) {
					error = true;
				}
				if (error == true) {
					$("#duree").css("border", border_false);
				} else {
					$("#duree").css("border", border_true);
				}
				activeForm(error);
				return error;
			}
		}

		function validDistance() {
			if ("{$experimentation.controle_enabled}" != 't') {
				return false;
			} else {
				/*
				 * Calcule et verifie les distances
				 */
				var errorDistance = false;
				/*
				 * Recherche les incoherences entre la longueur declaree et la longueur calculee
				 */
				var chalut = parseInt($("#distance_chalutee").val());
				var longueur = parseInt($("#longueur_trait_gps").val());
				var distanceMin = "{$experimentation.distance_min}";
				var distanceMax = "{$experimentation.distance_max}";
				$("#erreur_gps").text("");
				if (chalut > distanceMax || chalut < distanceMin) {
					errorDistance = true;
					$("#distance_chalutee").css("border", border_false);
					$("#distance_chalutee").attr("title", "la distance chalutée doit être comprise entre {$experimentation.distance_min} et {$experimentation.distance_max} m");
				}
				if (longueur > distanceMax || longueur < distanceMin) {
					errorDistance = true;
					$("#longueur_trait_gps").css("border", border_false);
					$("#longueur_trait_gps").attr("title", "la distance calculée doit être comprise entre {$experimentation.distance_min} et {$experimentation.distance_max} m");
				}

				var diff = Math.abs(chalut - longueur);
				var maxDeviation = "{$experimentation.max_allowed_distance_deviation}";
				var taux = parseInt(maxDeviation) / 100;
				if ((diff / chalut) > taux) {
					errorDistance = true;
					$("#erreur_gps").append("Écart > " + (taux * 100) + " % entre longueur chalutée déclarée et longueur calculée");
					$("#distance_chalutee").css("border", border_false);
					$("#longueur_trait_gps").css("border", border_false);
				}
				if (errorDistance == true) {
					$("#distance_chalutee").css("border", border_false);
					$("#longueur_trait_gps").css("border", border_false);
				} else {
					$("#distance_chalutee").css("border", border_true);
					$("#longueur_trait_gps").css("border", border_true);
				}
				activeForm(errorDistance);
				return errorDistance;
			}
		}

		function activeForm(flag) {
			/*
			 * Active ou desactive le bouton de validation du formulaire
			 */
			$("#validFormButton").prop("disabled", flag);
		}

		function populateDce() {
			var campagne = $("#fk_campagne_id option:selected").text();
			var a_campagne = campagne.split(":");
			$("#experimentation").val(a_campagne[1].trim());
		}


		$("#fk_materiel_id").change(function () {
			$("#engin").val($("#fk_materiel_id option:selected").text());
		});
		$(".gps_source").change(function () {
			var sourcename = $(this).attr("name");
			var destname = sourcename + "_dd";
			$('#' + destname).val(convertGPStoDD($(this).val()));
			calculLongueurTrait();
		});
		$(".gps").change(function () {
			calculLongueurTrait();
		});
		$(".vitesse").change(function () {
			calculVitesse();
		});
		$(".duree").change(function () {
			validDuree();
		});
		$(".salinite").change(function () {
			validSalinite();
		});
		$("#maree").change(function () {
			validMaree();
		});

		$("#fk_campagne_id").change(function () {
			populateDce();
		});

		/*
		 * Transformation des coordonnees RGFG95 en GPS
		 */
		$(".rgfg_deb").change(function () {
			var x = $("#rgfg95_deb_x").val();
			var y = $("#rgfg95_deb_y").val();
			if (x.length > 0 && y.length > 0) {
				var gps = proj4('EPSG:2972', 'EPSG:4326', [parseFloat(x), parseFloat(y)]);
				$("#pos_deb_long_dd").val(gps[0]);
				$("#pos_deb_lat_dd").val(gps[1]);
				calculLongueurTrait();
			}
		});

		$(".rgfg_fin").change(function () {
			var x = $("#rgfg95_fin_x").val();
			var y = $("#rgfg95_fin_y").val();
			if (x.length > 0 && y.length > 0) {
				var gps = proj4('EPSG:2972', 'EPSG:4326', [parseFloat(x), parseFloat(y)]);
				$("#pos_fin_long_dd").val(gps[0]);
				$("#pos_fin_lat_dd").val(gps[1]);
				calculLongueurTrait();
			}

		});
		/**
		 * Enregistrement du formulaire
		 */
		$("#traitChange").submit(function (event) {
			$("#" + hasfocus).trigger("change");
			$("#fk_materiel_id").trigger("change");
			/*
			 * Recherche si la salinite a ete renseignee
			 */
			var error = false;
			if (validSalinite() == true) {
				error = true;
			}
			if (validDuree() == true) {
				error = true;
			}
			if (validMaree() == true) {
				error = true;
			}
			var errorVitesse = calculVitesse();
			if (error == false && errorVitesse == true) {
				error = true;
			}
			var errorDistance = validDistance();
			if (error == false && errorDistance == true) {
				error = true;
			}
			/*
			 * Mise a jour du statut du trait
			 */
			$("#validite").val(1);
			/*
			 * Blocage de l'envoi du formulaire
			 */
			if (error == true)
				event.preventDefault();
		});

		$("#distance_chalutee").change(function () {
			var longueurGps = $("#longueur_trait_gps").val();
			var longueur = $("#distance_chalutee").val();
			if (longueurGps.length > 0 && longueur.length > 0) {
				validDistance();
			}
		});

		/*
		 * Lancement du calcul de la longueur du trait
		 */
		if ($("#trait_id").val() == 0 || $("#trait_id").val() == "") {
			populateDce();
			activeForm(true);
		}
		/*
		 * Mise a niveau de l'indicateur DCE pour les controles
		 */
		if ($("#experimentation").val() != "DCE") {
			isDCE = false;
		}
		calculLongueurTrait();
		calculVitesse();
		validDuree();
		validMaree();
	});

</script>
<h2>Modification d'un trait</h2>
<a href="traitList"> <img src="display/images/list.png" height="25">
	Retour à la liste
</a>
{if $data.trait_id > 0}
<a href="traitDisplay?trait_id={$data.trait_id}"> <img src="display/images/detail.png" height="25">
	Retour au détail du trait
</a>
{/if}
<div class="row">
	<div class="col-md-8">
		<form class="form-horizontal" id="traitChange" method="post" action="traitWrite">
			<input type="hidden" name="moduleBase" value="trait">
			<input type="hidden" id="trait_id" name="trait_id" value="{$data.trait_id}">
			<input type="hidden" id="validite" name="validite" value="{$data.validite}">
			<div class="form-group center">
				<button type="submit" id="validFormButton" class="btn btn-primary button-valid">Valider</button>
				{if $data.trait_id > 0 }
				<button class="btn btn-danger button-delete">Supprimer</button>
				{/if}
			</div>
			<!-- Tab box -->
			<ul class="nav nav-tabs" id="traitChangeTab" role="tabchange">
				<li class="nav-item active">
					<a class="nav-link traitChangeTab" id="tabchange-main" data-toggle="tab" role="tab"
						aria-controls="navchange-main" aria-selected="true" href="#navchange-main">
						{t}Données générales{/t}
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link traitChangeTab" id="tabchange-phy" href="#navchange-phy" data-toggle="tab"
						role="tab" aria-controls="navchange-phy" aria-selected="false">
						{t}Données physico-chimiques{/t}
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link traitChangeTab" id="tabchange-gps" href="#navchange-gps" data-toggle="tab"
						role="tab" aria-controls="navchange-gps" aria-selected="false">
						{t}Coordonnées GPS{/t}
					</a>
				</li>
			</ul>
			<div class="tab-content col-lg-12 form-horizontal" id="change-tabContent">
				<div class="tab-pane active in" id="navchange-main" role="tabpanel" aria-labelledby="tabchange-main">
					<div class="form-group">
						<label for="madate" class="control-label col-md-4">
							Date / heure du trait <span class="red">*</span> :
						</label>
						<div class="col-md-8">
							<input class="form-control datetimepicker" id="madate" name="madate"
								placeholder="1/4/16 09:20 ou 1/4 9:20" required value="{$data.madate}">
						</div>
					</div>
					<div class="form-group">
						<label for="fk_materiel_id" class="control-label col-md-4">
							Engin utilisé<span class="red">*</span> :
						</label>
						<div class="col-md-8">
							<input type="hidden" name="engin" id="engin" value="{$data.engin}">
							<select class="form-control" name="fk_materiel_id" id="fk_materiel_id">
								{section name=lst loop=$materiel}
								<option value="{$materiel[lst].materiel_id}" {if
									$materiel[lst].materiel_id==$data.fk_materiel_id}selected{/if}>
									{$materiel[lst].materiel_nom}</option>
								{/section}
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="station" class="control-label col-md-4">Station :</label>
						<div class="col-md-8">
							<input id="station" class="form-control commentaire" name="station" value="{$data.station}">
						</div>
					</div>
					<div class="form-group">
						<label for="ordre" class="control-label col-md-4">
							Ordre de pêche dans la journée<span class="red">*</span> :
						</label>
						<div class="col-md-8">
							<input name="ordre" id="ordre" class="form-control nombre" value="{$data.ordre}" required>
						</div>
					</div>
					<div class="form-group">
						<label for="experimentation" class="control-label col-md-4">Expérimentation :</label>
						<div class="col-md-8">
							<input class="form-control" id="experimentation" name="experimentation"
								value="{$data.experimentation}" readonly>
						</div>
					</div>
					<div class="form-group">
						<label for="" class="control-label col-md-4">
							Campagne<span class="red">*</span> :
						</label>
						<div class="col-md-8">
							<select class="form-control" id="fk_campagne_id" name="fk_campagne_id">
								{section name=lst loop=$campagne}
								<option value="{$campagne[lst].campagne_id}" {if
									$campagne[lst].campagne_id==$data.campagne_id}selected{/if}>
									{$campagne[lst].campagne_nom}:{$campagne[lst].experimentation_libelle}
								</option>
								{/section}
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="commentaire" class="control-label col-md-4">Commentaire :</label>
						<div class="col-md-8">
							<textarea class="form-control" id="commentaire"
								name="commentaire">{$data.commentaire}</textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="duree" class="control-label col-md-4">
							Durée du trait (mn)<span class="red">*</span> :
						</label>
						<div class="col-md-8">
							<input class="form-control nombre vitesse duree" id="duree" name="duree"
								value="{$data.duree}" required>
							(entre {$experimentation.duration_min} et {$experimentation.duration_max}')
						</div>
					</div>
					<div class="form-group">
						{if $campagne[0].code_agence == "GUY"}
						<label for="maree" class="control-label col-md-4">
							hauteur d'eau pleine mer<span class="red">*</span> :
						</label>
						<div class="col-md-8">
							<input class="form-control taux" id="maree" name="h_eau_pleine_mer"
								value="{$data.h_eau_pleine_mer}">
							(en mètres)
						</div>
						{else}
						<label for="maree" class="control-label col-md-4">
							Coefficient de marée<span class="red">*</span> :
						</label>
						<div class="col-md-8">
							<input class="form-control nombre" id="maree" name="maree" value="{$data.maree}">
							(entre 30 et 120)
						</div>
						{/if}
					</div>
					<div class="form-group">
						<label for="profondeur" class="control-label col-md-4">Profondeur de pêche (m) :</label>
						<div class="col-md-8">
							<input class="form-control taux " name="profondeur" value="{$data.profondeur}">
						</div>
					</div>
					<div class="form-group">
						<label for="distance_chalutee" class="control-label col-md-4">
							Distance réelle chalutée (m)<span class="red">*</span> :
						</label>
						<div class="col-md-8">
							<input class="form-control nombre vitesse" name="distance_chalutee" id="distance_chalutee"
								value="{$data.distance_chalutee}" required> {if $experimentation.distance_max > 0}
							({$experimentation.distance_min} - {$experimentation.distance_max})
							{/if}
						</div>
					</div>
					<div class="form-group">
						<label for="vitesse" class="control-label col-md-4">Vitesse calculée :</label>
						<div class="col-md-8">
							<input class="form-control" id="vitesse" readonly>
							(entre {$experimentation.speed_min} et {$experimentation.speed_max} m/mn)
						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="navchange-phy" role="tabpanel" aria-labelledby="tabchange-phy">
					<div class="form-group">
						<label for="temperature" class="control-label col-md-4">Température (°C) :</label>
						<div class="col-md-8">
							<input class="form-control taux " id="temperature" name="temperature"
								value="{$data.temperature}">
						</div>
					</div>
					<div class="form-group">
						<label for="oxygene" class="control-label col-md-4">Oxygène (% sat) :</label>
						<div class="col-md-8">
							<input class="form-control taux " id="oxygene" name="oxygene" value="{$data.oxygene}">
						</div>
					</div>
					<div class="form-group">
						<label for="salinite" class="control-label col-md-4">
							Salinité (PSU)<span class="red">*</span>:
						</label>
						<div class="col-md-8">
							<input id="salinite" class="form-control taux  salinite" id="salinite" name="salinite"
								value="{$data.salinite}">
						</div>
					</div>

					<div class="form-group">
						<label for="salinite_classe" class="control-label col-md-4">
							Classe de salinité<span class="red">*</span> :
						</label>
						<div class="col-md-8">
							<select class="salinite form-control" id="salinite_classe" name="salinite_classe">
								<option value="" {if $data.salinite_classe=="" }selected{/if}></option> {section
								name=lst
								loop=$salinite}
								<option value="{$salinite[lst].salinite_id}" {if
									$salinite[lst].salinite_id==$data.salinite_classe}selected{/if}>
									{$salinite[lst].salinite_libelle}</option>
								{/section}

							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="conductivite" class="control-label col-md-4">Conductivité (µS/cm) :</label>
						<div class="col-md-8">
							<input class="form-control nombre" id="conductivite" name="conductivite"
								value="{$data.conductivite}">
						</div>
					</div>
					<div class="form-group">
						<label for="ph" class="control-label col-md-4">pH :</label>
						<div class="col-md-8">
							<input class="form-control taux" id="ph" name="ph" value="{$data.ph}">
						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="navchange-gps" role="tabpanel" aria-labelledby="tabchange-gps">
					<div class="form-group">
						<label for="pos_deb_lat" class="control-label col-md-4">
							Point de début<span class="red">*</span> :
						</label>
						<div class="col-md-8" id="pos_deb">
							<table class="tablesaisie">
								{if $campagne[0].code_agence == "GUY"}
								<tr>
									<td>X (RGFG95) :</td>
									<td>
										<input class="rgfg_deb taux" name="rgfg95_deb_x" id="rgfg95_deb_x"
											value="{$data.rgfg95_deb_x}" autocomplete="off">
									</td>
									<td>
										Y : </td>
									<td><input class="rgfg_deb taux" name="rgfg95_deb_y" id="rgfg95_deb_y"
											value="{$data.rgfg95_deb_y}" autocomplete="off">
									</td>
								</tr>
							</table>
							<table class="tablesaisie">
								{/if}
								<tr>
									<th></th>
									<th>En DD.MM.mmmm</th>
									<th>En valeur numérique</th>
								</tr>
								<tr>
									<td>Lat :</td>
									<td>
										<input class="gps_source" name="pos_deb_lat" id="pos_deb_lat"
											value="{$data.pos_deb_lat}" placeholder="45°01,234N" autocomplete="off">
									</td>
									<td><input class="taux  gps" name="pos_deb_lat_dd" id="pos_deb_lat_dd"
											value="{$data.pos_deb_lat_dd}" required autocomplete="off"></td>
								</tr>
								<tr>
									<td>Long :</td>
									<td>
										<input class="gps_source" name="pos_deb_long" id="pos_deb_long"
											value="{$data.pos_deb_long}" placeholder="01°10,234W" autocomplete="off">
									</td>
									<td><input class="taux  gps" name="pos_deb_long_dd" id="pos_deb_long_dd"
											value="{$data.pos_deb_long_dd}" required autocomplete="off"></td>
								</tr>
							</table>
						</div>
						<div class="form-group">
							<label for="pos_fin" class="control-label col-md-4">
								Point de fin<span class="red">*</span> :
							</label>
							<div class="col-md-8" id="pos_fin">
								<table class="tablesaisie">
									{if $campagne[0].code_agence == "GUY"}
									<tr>
										<td>X (RGFG95) :</td>
										<td>
											<input class="rgfg_fin taux" name="rgfg95_fin_x" id="rgfg95_fin_x"
												value="{$data.rgfg95_fin_x}" autocomplete="off">
										</td>
										<td>Y:</td>
										<td>
											<input class="rgfg_fin taux" name="rgfg95_fin_y" id="rgfg95_fin_y"
												value="{$data.rgfg95_fin_y}" autocomplete="off">
										</td>
									</tr>
								</table>
								<table class="tablesaisie">
									{/if}
									<tr>
										<td>Lat :</td>
										<td>
											<input class="gps_source" name="pos_fin_lat" id="pos_findeb_lat"
												value="{$data.pos_fin_lat}" placeholder="45°01,234N" autocomplete="off">
										</td>
										<td><input class="taux  gps" name="pos_fin_lat_dd" id="pos_fin_lat_dd"
												value="{$data.pos_fin_lat_dd}" required autocomplete="off"></td>
									</tr>
									<tr>
										<td>Long :</td>
										<td>
											<input class="gps_source" name="pos_fin_long" id="pos_fin_long"
												value="{$data.pos_fin_long}" placeholder="01°10,234W"
												autocomplete="off">
										</td>
										<td><input class="taux  gps" name="pos_fin_long_dd" id="pos_fin_long_dd"
												value="{$data.pos_fin_long_dd}" required autocomplete="off"></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="form-group">
							<label for="longueur_trait_gps" class="control-label col-md-4">Longueur calculée (mètres)
								:</label>
							<div class="col-md-7">
								<input class="form-control" id="longueur_trait_gps" readonly><span
									id="erreur_gps"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							{include file="traits/traitMap.tpl"}
						</div>
					</div>
				</div>
			</div>
			{$csrf}
		</form>
	</div>

</div>

<span class="red">*</span>
<span class="messagebas">Champ obligatoire</span>