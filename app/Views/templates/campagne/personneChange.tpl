<script>
	$(document).ready(function() {
		$(".selectEdit").change( function () {
			$(this).next(".inputEdit").val($(this).val());
		} );

	});
</script>
<h2>Modification d'un responsable de campagne</h2>

<a href="personneList">Retour à la liste</a>
<div class="row">
<div class="col-md-12">
	<fieldset>
		<legend>Responsable</legend>

			<form class="form-horizontal" method="post" action="personneWrite">
				<input type="hidden" name="personne_id" value="{$data.personne_id}">
				<input type="hidden" name="moduleBase" value="personne">
				<input type="hidden" name="action" value="Write">

				<div class="form-group">
					<label for="nom"  class="control-label col-md-4">
						Nom - prénom<span class="red">*</span> :</label>
					<div class="col-md-8">
						<input name="nom" id="nom" required	value="{$data.nom}">
						&nbsp;
						<input name="prenom" value="{$data.prenom}">
					</div>
				</div>
				<div class="form-group">
				<label for="institut"  class="control-label col-md-4">Institut<span class="red">*</span> :</label>
				<div class="col-md-8">
				<select id="institut" class="selectEdit form-control">
				<option value="" {if $data.institut == ""}selected{/if}>
				{section name=lst loop=$institut}
				<option value="{$institut[lst].field}" {if $data.institut == $institut[lst].field}selected{/if}>
				{$institut[lst].field}
				</option>
				{/section}
				</select>
				<input class="inputEdit form-control" name="institut" value="{$data.institut}" required>
				</div>
				</div>
				<div class="form-group">
				<label for="adresse"  class="control-label col-md-4">Adresse :</label>
				<div class="col-md-8">
				<textarea class="form-control" id="adresse" name="adresse">{$data.adresse}</textarea>
				</div>
				</div>
				<div class="form-group">
					<label for="telephone"  class="control-label col-md-4">
						Téléphone :
					</label>
					<div class="col-md-8">
						<input  class="form-control" id="telephone" name="téléphone" value="{$data.telephone}">
					</div>
				</div>
				<div class="form-group">
					<label for="email"  class="control-label col-md-4">
						Mail :</label>
					<div class="col-md-8">
						<input name="email" id="email" class="form-control" value="{$data.email}">
					</div>
				</div>

		<div class="form-group center">
      <button type="submit" class="btn btn-primary button-valid">Valider</button>
      {if $data.personne_id > 0 && $data.children == 0}
      <button class="btn btn-danger button-delete">Supprimer</button>
      {/if}
 		</div>
		{$csrf}</form>
	</fieldset>
</div>
</div>

<span class="red">*</span>
<span class="messagebas">Champ obligatoire</span>
