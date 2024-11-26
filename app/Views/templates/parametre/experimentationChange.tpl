<h2>Modification d'une expérimentation</h2>

<a href="experimentationList">Retour à la liste</a>
<div class="row">
	<form class="form-horizontal col-md-6" method="post" action="experimentationWrite">
		<fieldset>
			<legend>Expérimentation</legend>
			<input type="hidden" name="moduleBase" value="experimentation">
			<input type="hidden" name="experimentation_id" value="{$data.experimentation_id}">

			<div class="form-group">
				<label for="experimentation_libelle" class="control-label col-md-4">
					Nom de l'expérimentation <span class="red">*</span> :
				</label>
				<div class="col-md-8">
					<input class="form-control" id="experimentation_libelle" name="experimentation_libelle" required
						value="{$data.experimentation_libelle}">
				</div>
			</div>
			<div class="form-group">
				<label for="controle_enabled" class="control-label col-md-4">Contrôles activés <span
						class="red">*</span> :</label>
				<div class="col-md-8" id="controle_enabled">
					<input type="radio" name="controle_enabled" value="1" {if $data.controle_enabled=='t'
						}checked{/if}>oui
					<input type="radio" name="controle_enabled" value="0" {if $data.controle_enabled !='t'
						}checked{/if}>non
				</div>
			</div>
			<div class="form-group">
				<label for="" class="control-label col-md-4">
					Vitesse (m/mn) <span class="red">*</span> :
				</label>
				<div class="col-md-8">
					<input class="form-control nombre" name="speed_min" value="{$data.speed_min}" placeholder="min"> /
					<input class="form-control nombre" name="speed_max" value="{$data.speed_max}" placeholder="max">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="control-label col-md-4">
					Durée (mn) <span class="red">*</span> :
				</label>
				<div class="col-md-8">
					<input class="form-control nombre" name="duration_min" value="{$data.duration_min}"
						placeholder="min"> /
					<input class="form-control nombre" name="duration_max" value="{$data.duration_max}"
						placeholder="max">
				</div>
			</div>
			<div class="form-group">
				<label for="distance" class="control-label col-md-4">
					Distance (m) <span class="red">*</span> :
				</label>
				<div class="col-md-8" id="distance">
					<input class="form-control nombre" name="distance_min" value="{$data.distance_min}"
						placeholder="min"> /
					<input class="form-control nombre" name="distance_max" value="{$data.distance_max}"
						placeholder="max">
				</div>
			</div>
			<div class="form-group">
				<label for="max_allowed_distance_deviation" class="control-label col-md-4">Taux d'erreur admissible
					(distance calculée - distance déclarée, en %)<span class="red">*</span> :</label>
				<div class="col-md-8">
					<input class="form-control taux" id="max_allowed_distance_deviation"
						name="max_allowed_distance_deviation" value="{$data.max_allowed_distance_deviation}">
				</div>
			</div>
			<div class="form-group"></div>

			<div class="form-group center">
				<button type="submit" class="btn btn-primary button-valid">Valider</button>
				{if $data.experimentation_id > 0 }
				<button class="btn btn-danger button-delete">Supprimer</button>
				{/if}
			</div>
		</fieldset>
		{$csrf}
	</form>
</div>

<span class="red">*</span>
<span class="messagebas">Champ obligatoire</span>