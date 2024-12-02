<script>
	$(document).ready( function() {
		function testLongueur() {
			var retour = true;
			var longueur=$("#longueur").val();
			var ltmax = $("#lt_max").text();
			//console.log("longueur",longueur);
			//console.log("lt_max",ltmax);
			var retour = true;
			if (longueur.length > 0 && ltmax.length > 0) {
				if (parseFloat(longueur) > parseFloat(ltmax)) {
				retour = false;
				}
				//console.log("retour", retour);
			return retour;
			}
		}
		var border_false = "1px solid #CC0000";
		var border_true = $("#especeSearch").css("border");
		$("#longueur").keyup( function() {
				if (testLongueur() == false) {
					$(this).css("border", border_false);
				} else {
					$(this).css("border", border_true);
				}
		});
		$("#individuChange").submit(function(event) {
			if (testLongueur() == false) {
				/*
				 * Blocage de l'envoi du formulaire
				 */
				event.preventDefault();
			}
		});
	});
</script>

	<form id="individuChange" method="post" action="individuWrite" class="form-horizontal col-md-12">
		<input type="hidden" name="fk_trait_id" value="{$dataEchan.fk_trait_id}">
		<input type="hidden" name="ech_id" value="{$dataEchan.ech_id}">
			<input type="hidden" name="moduleBase" value="individu">

		<input type="hidden" name="fk_ech_id" value="{$dataEchan.ech_id}">
		<input type="hidden" name="ind_id" value="{$individu.ind_id}">
		<div class="form-group">
			<label for="longueur"  class="control-label col-md-4">
				Longueur (mm)<span class="red">*</span> :
			</label>
			<div class="col-md-8">
				<input id="longueur" class="taux form-control" name="longueur" value="{$individu.longueur}" required autocomplete="off"
					autofocus>
			</div>
			<label for="poids"  class="control-label col-md-4">Masse (g) :</label>
			<div class="col-md-8">
				<input class="taux form-control" id="poids" name="poids" value="{$individu.poids}"autocomplete="off" >
			</div>
		</div>

	<div class="form-group center">
      <button type="submit" class="btn btn-primary button-valid">Valider</button>
      {if $individu.ind_id > 0 }
      <button class="btn btn-danger button-delete">Supprimer</button>
      {/if}
     </div>
{$csrf}</form>
