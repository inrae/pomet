<script>
	$(document).ready(function () {
		$(".selectEdit").change(function () {
			$(this).next(".inputEdit").val($(this).val());
		});

	});
</script>
<h2>Modification d'un matériel</h2>

<a href="materielList">Retour à la liste</a>
<div class="row">
	<form method="post" class="form-horizontal col-md-6" action="materielWrite">
		<input type="hidden" name="container_id" value="{$data.container_id}">
		<input type="hidden" name="moduleBase" value="materiel">
		<fieldset>
			<legend>Engin de pêche</legend>
			<input type="hidden" name="materiel_id" value="{$data.materiel_id}">

			<div class="form-group">
				<label for="materiel_nom" class="control-label col-md-4">
					Nom de l'engin de pêche <span class="red">*</span> :
				</label>
				<div class="col-md-8">
					<input class="form-control" id="materiel_nom" name="materiel_nom" required
						value="{$data.materiel_nom}">
				</div>
			</div>
			<div class="form-group">
				<label for="materiel_type" class="control-label col-md-4">Type de matériel :</label>
				<div class="col-md-8">
					<select id="materiel_type" class="selectEdit form-control">
						<option value="" {if $data.materiel_type=="" }selected{/if}>
							{section name=lst loop=$materiel_type}
						<option value="{$materiel_type[lst].field}" {if
							$data.materiel_type==$materiel_type[lst].field}selected{/if}>
							{$materiel_type[lst].field}</option>
						{/section}
					</select>
					<input class="inputEdit form-control" name="materiel_type" value="{$data.materiel_type}">
				</div>
			</div>
			<div class="form-group">
				<label for="materiel_code" class="control-label col-md-4">
					Code :
				</label>
				<div class="col-md-8">
					<input class="form-control" id="materiel_code" name="materiel_code" value="{$data.materiel_code}">
				</div>
			</div>
			<div class="form-group">
				<label for="materiel_description" class="control-label col-md-4">Description :</label>
				<div class="col-md-8">
					<textarea class="form-control" id="materiel_description"
						name="materiel_description">{$data.materiel_description}</textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="experimentation_id" class="control-label col-md-4">Expérimentation :</label>
				<div class="col-md-8">
					<select id="experimentation_id" name="experimentation_id" class="form-control">
						<option value="" {if $data.experimentation_id=="" }selected{/if}>
							{section name=lst loop=$experimentation}
						<option value="{$experimentation[lst].experimentation_id}" {if
							$data.experimentation_id==$experimentation[lst].experimentation_id}selected{/if}>
							{$experimentation[lst].experimentation_libelle}
						</option>
						{/section}
					</select>
				</div>
			</div>
			<div class="form-group center">
				<button type="submit" class="btn btn-primary button-valid">Valider</button>
				{if $data.materiel_id > 0 }
				<button class="btn btn-danger button-delete">Supprimer</button>
				{/if}
			</div>
		</fieldset>
		{$csrf}
	</form>
</div>

<span class="red">*</span>
<span class="messagebas">Champ obligatoire</span>