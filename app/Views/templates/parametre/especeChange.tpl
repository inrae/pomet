<script>
	$(document).ready(
		function () {
			$(".form-control selectEdit ").change(function () {
				$(this).next(".form-control").val($(this).val());
			});
			/*
			 * Fonction de recherche des select lies au parent
			 */
			function fieldSearch(id, parentId, valeur) {
				var subid;
				var url = "especeGetValues";
				switch (id) {
					case "phylum":
						subid = "subphylum";
						break;
					case "subphylum":
						subid = "classe";
						break;
					case "classe":
						subid = "ordre";
						break;
					case "ordre":
						subid = "famille";
						break;
					case "famille":
						subid = "genre";
				}
				var options = "";
				$.getJSON(url, {
					"value": valeur,
					"field": subid,
					"parentField": parentId
				}, function (data) {
					options = '<option value="" selected></option>';
					for (var i = 0; i < data.length; i++) {
						options += '<option value="' + data[i].field + '">'
							+ data[i].field + '</option>';
					}
					;
					$("#" + subid).html(options);
				});
				if (subid.length > 0) {
					fieldSearch(subid, parentId, valeur);
				}
			}
			;

			$(".selection").change(function () {
				var currentId = $(this).attr('id');
				var valeur = $(this).val();
				fieldSearch(currentId, currentId, valeur);
			});

		});
</script>
<h2>Modification d'une espèce</h2>

<a href="especeList"><img src="display/images/list.png" height="25">Retour à la liste</a>
{if $data.espece_id > 0}
&nbsp;
<a href="especeDisplay?espece_id={$data.espece_id}"><img src="display/images/detail.png" height="25">Retour au
	détail</a>
{/if}
<div class="formSaisie">
	<form class="form-horizontal protoform" method="post" action="especeWrite">
		<input type="hidden" name="moduleBase" value="espece">
		<input type="hidden" name="espece_id" value="{$data.espece_id}">
		<input type="hidden" name="idParent" value="{$data.idParent}">
		<fieldset>
			<legend>Espèce</legend>
			<div>
				<div class="form-group">
					<label for="nom" class="control-label col-md-4">
						Nom latin <span class="red">*</span> :
					</label>
					<div class="col-md-8">
						<input class="form-control" id="nom" name="nom" required value="{$data.nom}">
					</div>
				</div>
				<div class="form-group">
					<label for="nom_fr" class="control-label col-md-4">Nom français :</label>
					<div class="col-md-8">
						<input id="nom_fr" name="nom_fr" value="{$data.nom_fr}" class="form-control">
					</div>
				</div>

				<div class="form-group">
					<label for="auteur" class="control-label col-md-4">Auteur :</label>
					<div class="col-md-8">
						<select id="auteur" class="form-control selectEdit ">
							<option value="" {if $data.auteur=="" }selected{/if}>{section name=lst
								loop=$auteur}
							<option value="{$auteur[lst].field}" {if $data.auteur==$auteur[lst].field}selected{/if}>
								{$auteur[lst].field}
							</option>
							{/section}
						</select>
						<input class="form-control" name="auteur" value="{$data.auteur}">
					</div>
				</div>

				<div class="form-group">
					<label for="phylum" class="control-label col-md-4">Phylum :</label>
					<div class="col-md-8">
						<select id="phylum" class="form-control selectEdit  selection">
							<option value="" {if $data.phylum=="" }selected{/if}>{section name=lst
								loop=$phylum}
							<option value="{$phylum[lst].field}" {if $data.phylum==$phylum[lst].field}selected{/if}>
								{$phylum[lst].field}
							</option>
							{/section}
						</select>
						<input class="form-control" name="phylum" value="{$data.phylum}">
					</div>
				</div>

				<div class="form-group">
					<label for="" class="control-label col-md-4">Subphylum :</label>
					<div class="col-md-8">
						<select id="subphylum" class="form-control selectEdit  selection">
							<option value="" {if $data.subphylum=="" }selected{/if}>{section name=lst
								loop=$subphylum}
							<option value="{$subphylum[lst].field}" {if
								$data.subphylum==$subphylum[lst].field}selected{/if}>
								{$subphylum[lst].field}
							</option>
							{/section}
						</select>
						<input class="form-control" name="subphylum" value="{$data.subphylum}">
					</div>
				</div>

				<div class="form-group">
					<label for="" class="control-label col-md-4">Classe :</label>
					<div class="col-md-8">
						<select id="classe" class="form-control selectEdit  selection">
							<option value="" {if $data.classe=="" }selected{/if}>{section name=lst
								loop=$classe}
							<option value="{$classe[lst].field}" {if $data.classe==$classe[lst].field}selected{/if}>
								{$classe[lst].field}
							</option>
							{/section}
						</select>
						<input class="form-control" name="classe" value="{$data.classe}">
					</div>
				</div>

				<div class="form-group">
					<label for="" class="control-label col-md-4">Ordre :</label>
					<div class="col-md-8">
						<select id="ordre" class="form-control selectEdit  selection">
							<option value="" {if $data.ordre=="" }selected{/if}>{section name=lst
								loop=$ordre}
							<option value="{$ordre[lst].field}" {if $data.ordre==$ordre[lst].field}selected{/if}>
								{$ordre[lst].field}
							</option>
							{/section}
						</select>
						<input class="form-control" name="ordre" value="{$data.ordre}">
					</div>
				</div>


				<div class="form-group">
					<label for="" class="control-label col-md-4">Famille :</label>
					<div class="col-md-8">
						<select id="famille" class="form-control selectEdit  selection">
							<option value="" {if $data.famille=="" }selected{/if}>{section name=lst
								loop=$famille}
							<option value="{$famille[lst].field}" {if $data.famille==$famille[lst].field}selected{/if}>
								{$famille[lst].field}
							</option>
							{/section}
						</select>
						<input class="form-control" name="famille" value="{$data.famille}">
					</div>
				</div>

				<div class="form-group">
					<label for="genre" class="control-label col-md-4">Genre :</label>
					<div class="col-md-8">
						<select id="genre" class="form-control selectEdit  selection">
							<option value="" {if $data.genre=="" }selected{/if}>{section name=lst
								loop=$genre}
							<option value="{$genre[lst].field}" {if $data.genre==$genre[lst].field}selected{/if}>
								{$genre[lst].field}
							</option>
							{/section}
						</select>
						<input class="form-control" name="genre" value="{$data.genre}">
					</div>
				</div>

				<div class="form-group">
					<label for="code_sandre" class="control-label col-md-4">Code Sandre :</label>
					<div class="col-md-8">
						<input id="code_sandre" name="code_sandre" value="{$data.code_sandre}"
							class="form-control num5 ">
					</div>
				</div>
				<div class="form-group">
					<label for="code_perm_ifremer" class="control-label col-md-4">Code Perm Ifremer :</label>
					<div class="col-md-8">
						<input id="code_perm_ifremer" name="code_perm_ifremer" value="{$data.code_perm_ifremer}"
							class="form-control num5 ">
					</div>
				</div>

				<!-- Gestion des guildes -->
				<fieldset>
					<legend>Guilde</legend>
					<div class="form-group">
						<label for="guilde_ecologique_dce2007" class="control-label col-md-4">guilde écologique DCE 2007
							:</label>
						<div class="col-md-8">
							<input class="form-control" id="guilde_ecologique_dce2007" name="guilde_ecologique_dce2007"
								value="{$guilde.guilde_ecologique_dce2007}">
						</div>
					</div>
					<div class="form-group">
						<label for="guilde_trophique_dce2007" class="control-label col-md-4">guilde trophique DCE 2007
							:</label>
						<div class="col-md-8">
							<input class="form-control" id="guilde_trophique_dce2007" name="guilde_trophique_dce2007"
								value="{$guilde.guilde_trophique_dce2007}">
						</div>
					</div>
					<div class="form-group">
						<label for="guilde_trophique_lp" class="control-label col-md-4">guilde trophique lp :</label>
						<div class="col-md-8">
							<input class="form-control" id="guilde_trophique_lp" name="guilde_trophique_lp"
								value="{$guilde.guilde_trophique_lp}">
						</div>
					</div>
					<div class="form-group">
						<label for="index_trophique_fb2006" class="control-label col-md-4">Index trophique FishBase 2006
							:</label>
						<div class="col-md-8">
							<input id=index_trophique_fb2006" class="form-control taux " name="index_trophique_fb2006"
								value="{$guilde.index_trophique_fb2006}">
						</div>
					</div>
					<div class="form-group">
						<label for="repartition_dce2007" class="control-label col-md-4">Répartition DCE 2007 :</label>
						<div class="col-md-8">
							<input class="form-control" id="repartition_dce2007" name="repartition_dce2007"
								value="{$guilde.repartition_dce2007}">
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>Guilde wiser</legend>
					<div class="form-group">
						<label for="Ecological_guild" class="control-label col-md-4">guilde écologique :</label>
						<div class="col-md-8">
							<input class="form-control" id="Ecological_guild" name="Ecological_guild"
								value="{$wiser.Ecological_guild}">
						</div>
					</div>
					<div class="form-group">
						<label for="Position_guild" class="control-label col-md-4">Position :</label>
						<div class="col-md-8">
							<input class="form-control" id="Position_guild" name="Position_guild"
								value="{$wiser.Position_guild}">
						</div>
					</div>
					<div class="form-group">
						<label for="Trophic_guild" class="control-label col-md-4">guilde trophique :</label>
						<div class="col-md-8">
							<input class="form-control" id="Trophic_guild" name="Trophic_guild"
								value="{$wiser.Trophic_guild}">
						</div>
					</div>
					<div class="form-group">
						<label for="trophic_index_fishbase" class="control-label col-md-4">Index trophique FishBase
							:</label>
						<div class="col-md-8">
							<input class="form-control" id="trophic_index_fishbase" name="trophic_index_fishbase"
								value="{$wiser.trophic_index_fishbase}">
						</div>
					</div>

				</fieldset>
				<br>
				<!-- Gestion des données morphologiques -->
				{if !empty($size)}
				<input type="hidden" name="species_id" value="{$size.species_id}">
				<fieldset>
					<legend>Données morphologiques</legend>
					<div class="form-group">
						<label for="lt_maturity" class="control-label col-md-4">Longueur totale à maturité (cm)
							:</label>
						<div class="col-md-8">
							<input id="lt_maturity" name="lt_maturity" class="form-control taux "
								value="{$size.lt_maturity}">
						</div>
					</div>
					<div class="form-group">
						<label for="age_maturity" class="control-label col-md-4">Age à maturité :</label>
						<div class="col-md-8">
							<input id="age_maturity" name="age_maturity" class="form-control taux "
								value="{$size.age_maturity}">
						</div>
					</div>
					<div class="form-group">
						<label for="lt_max" class="control-label col-md-4">Longueur totale maxi (cm) :</label>
						<div class="col-md-8">
							<input id="lt_max" name="lt_max" class="form-control taux " value="{$size.lt_max}">
						</div>
					</div>
					<div class="form-group">
						<label for="age_max" class="control-label col-md-4">Age maxi :</label>
						<div class="col-md-8">
							<input id="age_max" name="age_max" class="form-control taux " value="{$size.age_max}">
						</div>
					</div>
					<div class="form-group">
						<label for="iucn" class="control-label col-md-4">Statut IUCN :</label>
						<div class="col-md-8">
							<input class="form-control" id="iucn" name="iucn" value="{$size.iucn}">
						</div>
					</div>
					<div class="form-group">
						<label for="spawn_frequence" class="control-label col-md-4">Nombre d'années entre deux
							reproductions :</label>
						<div class="col-md-8">
							<input id="spawn_frequence" name="spawn_frequence" class="form-control nombre"
								value="{$size.spawn_frequence}">
						</div>
					</div>
					<div class="form-group">
						<label for="repro_guild" class="control-label col-md-4">Guilde de reproduction :</label>
						<div class="col-md-8">
							<input id="repro_guild" name="repro_guild" class="form-control nombre"
								value="{$size.repro_guild}">
						</div>
					</div>
					<div class="form-group">
						<label for="parent_care" class="control-label col-md-4">Les parents prennent soin des juvéniles
							?</label>
						<div class="col-md-8" id="parent_care">
							<div class="radio">
								<input type="radio" name="parent_care" value="0" {if
									$size.parent_care==0}checked{/if}>non<br>
								<input type="radio" name="parent_care" value="1" {if
									$size.parent_care==1}checked{/if}>oui
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="fecondity_max" class="control-label col-md-4">Fécondité max :</label>
						<div class="col-md-8">
							<input id="fecondity_max" name="fecondity_max" class="form-control taux "
								value="{$size.fecondity_max}">
						</div>
					</div>
					<div class="form-group">
						<label for="egg_size" class="control-label col-md-4">Taille des oeufs (mm) :</label>
						<div class="col-md-8">
							<input id="egg_size" name="egg_size" class="form-control taux " value="{$size.egg_size}">
						</div>
					</div>
					<div class="form-group">
						<label for="swimming_id" class="control-label col-md-4">Type de nage :</label>
						<div class="col-md-8">
							<select class="form-control" id="swimming_id" name="swimming_id">
								<option value="" {if $size.swimming_id=="" }selected{/if}></option> {section name=lst
								loop=$swimming}
								<option value="{$swimming[lst].swimming_id}" {if
									$size.swimming_id==$swimming[lst].swimming_id}selected{/if}>
									{$swimming[lst].swimming_name}
									{/section}
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="resilience_id" class="control-label col-md-4">Résilience :</label>
						<div class="col-md-8">
							<select class="form-control" id="resilience_id" name="resilience_id">
								<option value="" {if $size.resilience_id=="" }selected{/if}></option> {section name=lst
								loop=$resilience}
								<option value="{$resilience[lst].resilience_id}" {if
									$size.resilience_id==$resilience[lst].resilience_id}selected{/if}>
									{$resilience[lst].resilience_name}
									{/section}
							</select>
						</div>
					</div>
				</fieldset>
				{/if}
				<div class="form-group center">
					<button type="submit" class="btn btn-primary button-valid">Valider</button>
					{if $data.espece_id > 0 && $data.children == 0}
					<button class="btn btn-danger button-delete">Supprimer</button>
					{/if}
				</div>

			</div>
		</fieldset>
		{$csrf}
	</form>
</div>

<span class="red">*</span>
<span class="messagebas">Champ obligatoire</span>