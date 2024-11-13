<script>
	$(document).ready(
			function() {
				$("#especeSearch").keyup(
						function() {
							var nom = $(this).val();
							if (nom.length > 2) {
								var options = "";
								var url = "index.php";
								$.getJSON(url, {
									"module" : "especeSearch",
									"nom" : nom
								}, function(data) {
									//options = '<option value="" selected></option>';
									for (var i = 0; i < data.length; i++) {
										options += '<option value="' + data[i].espece_id + '">'
												+ data[i].nom;
										if (data[i].nom_fr.length > 0) {
											options += ' (' + data[i].nom_fr + ')';
										}
										options += '</option>';
									}
									;
									$("#espece_id").html(options);
								});
							}
						});
				var border_false = "1px solid #CC0000";
				var border_true = $("#especeSearch").css("border");

				function testNt() {
					var error = false;
					var nbtotal = $("#nt").val();
					if (nbtotal.length == 0) nbtotal = 0;
					if (parseInt($("#totalPoisson").text()) > parseInt(nbtotal)) {
						$("#nt").css("border", border_false);
						error = true;
					} else {
						$("#nt").css("border", border_true);
					}
					return error;
				}

				function testMasse() {
					var error = false;
					var mtotal = $("#pt").val();
					if (mtotal.length == 0) mtotal = 0;
					if (parseInt($("#totalMasse").text()) > parseInt(mtotal)) {
						$("#pt").css("border", border_false);
						error = true;
					} else {
						$("#pt").css("border", border_true);
					}
					return error;
				}

				/*
				 * Teste si les donnees du formulaire sont coherentes
				 */
				$("#echanChange").submit(function(event) {
					if (testNt() == true || testMasse() == true) {
						/*
						 * Blocage de l'envoi du formulaire
						 */
						event.preventDefault();
					}
				});

				$("#nt").change(function() {
					testNt();
				});

				$("#pt").change(function() {
					testMasse();
				});
			});
</script>

<h2>Modification d'un échantillon</h2>
<a href="traitList"> <img src="display/images/list.png" height="25">
	Retour à la liste des traits
</a>
&nbsp;
<a href="traitDisplay?trait_id={$dataTrait.trait_id}"> <img
	src="display/images/detail.png" height="25"
> Retour au détail du trait
</a>
{if $dataEchan.ech_id > 0 }
<a href="echantillonChange?ech_id=0&trait_id={$dataTrait.trait_id}">
<img src="display/images/new.png" height="25">Nouvelle espèce...
</a>
{/if}
<div class="row">
<div class="col-md-6">
{include file="traits/traitCartouche.tpl"}
</div>
</div>
<div class="row">
<div class="col-md-6">
	<fieldset>
		<legend>Données concernant l'échantillon</legend>
		<form id="echanChange" method="post" action="echantillonWrite" class="form-horizontal col-md-8">
			<input type="hidden" name="fk_trait_id" value="{$dataEchan.fk_trait_id}">
			<input type="hidden" name="trait_id" value="{$dataEchan.fk_trait_id}">
			<input type="hidden" name="moduleBase" value="echantillon">
			<input type="hidden" name="ech_id" value="{$dataEchan.ech_id}">

			<div class="form-group">
				<label for="especeSearch"  class="control-label col-md-4">
					Espèce <span class="red">*</span> :
				</label>
				<div class="col-md-8">
					<input id="especeSearch" class="form-control" placeholder="Espèce à rechercher"
						autocomplete="off" {if $dataEchan.ech_id==0}autofocus{/if}
					> <select id="espece_id" name="espece_id"  class="form-control" >
						<option value="{$dataEchan.espece_id}">{$dataEchan.nom}{if
							strlen($dataEchan.nom_fr)>0}({$dataEchan.nom_fr}){/if}</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="nt"  class="control-label col-md-4">
					Nombre d'individus capturés<span class="red">*</span> :
				</label>
				<div class="col-md-8">
					<input class="nombre form-control" id="nt" name="nt" value="{$dataEchan.nt}" autocomplete="off" required>
				</div>
			</div>
			<div class="form-group">
				<label for="pt"  class="control-label col-md-4">Masse totale des individus (g) :</label>
				<div class="col-md-8">
					<input class="taux form-control" id="pt" name="pt" autocomplete="off" value="{$dataEchan.pt}">
				</div>
			</div>
		<div class="form-group center">
      	<button type="submit" class="btn btn-primary button-valid">Valider</button>
		{if $dataEchan.ech_id > 0}
      	<button class="btn btn-danger button-delete">Supprimer</button>
		{/if}
		</div>
	{$csrf}</form>

	</fieldset>
</div>

</div>

{if $dataEchan.ech_id > 0}
<div class="row">
<!-- Affichage et saisie des individus -->
<div class="col-md-6">
<fieldset>
<legend>Poissons mesurés{if strlen($dataEchan.lt_max) > 0} (Taille max :
<span id="lt_max">{$dataEchan.lt_max* 10}</span> mm){/if}</legend>
<div class="row">
<div class="col-md-12">
{include file="traits/individuChange.tpl"}
</div>
</div>
<div class="row">
<div class="col-md-6">
{include file="traits/individuList.tpl"}
</div>
</div>
</fieldset>
</div>
</div>
{/if}
<span class="red">*</span>
<span class="messagebas">Champ obligatoire</span>
