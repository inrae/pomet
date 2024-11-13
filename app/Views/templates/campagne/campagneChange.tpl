<script>
	$(document).ready(function() {
		$( ".generate" ).change(function () {
			var nom = $( "#fk_masse_eau option:selected" ).text() + "_"
						+ $( "#saison" ).val() + "_"
						+ $( "#annee" ).val();
			$( "#campagne_nom" ).val(nom);
		});
	});
</script>
<h2>Modification d'une campagne</h2>

<a href="campagneList">Retour à la liste</a>
<div class="row">
<div class="col-lg-10">
	<fieldset>
		<legend>Campagne</legend>


			<form class="form-horizontal" method="post" action="campagneWrite">
				<input type="hidden" name="campagne_id" value="{$data.campagne_id}">
				<input type="hidden" name="moduleBase" value="campagne">

				<div class="form-group">
					<label for="campagne_nom"  class="control-label col-md-4">
						Nom de la campagne (généré automatiquement) :
					</label>
					<div class="col-md-8">
						<input class="form-control" id="campagne_nom" name="campagne_nom" readonly value="{$data.campagne_nom}">

					</div>
				</div>

				<div class="form-group">
				<label for="fk_masse_eau"  class="control-label col-md-4">
				Masse d'eau<span class="red">*</span> :
				</label>
				<div class="col-md-8">
				<select class="generate form-control" name="fk_masse_eau"  id="fk_masse_eau" autofocus>
				{section name=lst loop=$masse_eau}
				{strip}
				<option value="{$masse_eau[lst].masse_eau_id}" {if $data.fk_masse_eau == $masse_eau[lst].masse_eau_id}selected{/if}>
				{$masse_eau[lst].masse_eau}
				</option>{/strip}
				{/section}
				</select>
				</div>
				</div>

				<div class="form-group"><label for="annee"  class="control-label col-md-4">Année<span class="red">*</span> :</label>
				<div class="col-md-8">
				<select class="generate form-control" name="annee"  id="annee">
				{foreach from=$annees item=annee}
				<option value="{$annee}" {if $data.annee == $annee}selected{/if}>
				{$annee}
				</option>
				{/foreach}
				</select>
				</div>
				</div>
				<div class="form-group"><label for="saison"  class="control-label col-md-4">Saison<span class="red">*</span> :</label>
				<div class="col-md-8">
				<select class="generate form-control" name="saison"  id="saison">
				{foreach from=$saisons item=saison}
				<option value="{$saison}" {if $data.saison == $saison}selected{/if}>
				{$saison}
				</option>
				{/foreach}
				</select>
				</div>
				</div>

				<div class="form-group">
				<label for="fk_personne"  class="control-label col-md-4">
				Responsable :
				</label>
				<div class="col-md-8">
				<select  class="form-control" name="fk_personne"  id="fk_personne">
				{section name=lst loop=$personne}
				<option value="{$personne[lst].personne_id}" {if $data.fk_personne == $personne[lst].personne_id}selected{/if}>
				{$personne[lst].nom} {$personne[lst].prenom} ({$personne[lst].institut})
				</option>
				{/section}
				</select>
				</div>
				</div>

				<div class="form-group">
				<label for="experimentation_id"  class="control-label col-md-4">
				Expérimentation :
				</label>
				<div class="col-md-8">
				<select  class="form-control" name="experimentation_id"  id="experimentation_id">
				<option value="" {if $data.experimentation_id ==""}selected{/if}></option>
				{section name=lst loop=$experimentation}
				<option value="{$experimentation[lst].experimentation_id}" {if $data.experimentation_id == $experimentation[lst].experimentation_id}selected{/if}>
				{$experimentation[lst].experimentation_libelle}
				</option>
				{/section}
				</select>
				</div>
				</div>

				<div class="form-group">
				<label for="is_actif"  class="control-label col-md-4">
				Campagne utilisée en saisie de traits ?
				</label>
				<div class="col-md-8" id="is_actif">
				<div class="radio">
				<label>
				<input type="radio" name="is_actif" value="1" {if $data.is_actif == 1}checked{/if}>oui
				</label>
				<label>
				<input type="radio" name="is_actif" value="0" {if $data.is_actif == 0}checked{/if}>non
				</label>
				</div>
				</div>
				</div>

				<fieldset><legend>Logins autorisés à modifier les traits de la campagne</legend>
				<div class="row col-md-12" >

				{section name=lst loop=$logins}
				<div class="panel panel-default col-md-3">

				<div class="col-sm-10 right">{$logins[lst].logindetail}</div>
				<div class="col-sm-2 center">
				<input type="checkbox" name="acllogin_id[]" value="{$logins[lst].acllogin_id}" {if $logins[lst].is_selected == 1}checked{/if}>
				</div>
				</div>
				{/section}
				</div>
				</fieldset>

				<div class="form-group center">
      <button type="submit" class="btn btn-primary button-valid">Valider</button>
      {if $data.campagne_id > 0 && $data.traits == 0}
      <button class="btn btn-danger button-delete">Supprimer</button>
      {/if}
 </div>
			{$csrf}</form>
	</fieldset>
</div>
</div>
<span class="red">*</span>
<span class="messagebas">Champ obligatoire</span>
