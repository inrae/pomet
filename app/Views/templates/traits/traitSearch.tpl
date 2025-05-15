<script>
	$(document).ready(function () {
		$(".selectcamp").change(function () {
			var url = "campagneSearchTrait";
			var options = "";
			var masse = $("#masse_eau_id").val();
			var annee = $("#annee").val();
			var saison = $("#saison").val();
			$.getJSON(url, { "masse_eau_id": masse, "annee": annee, "saison": saison },
				function (data) {

					for (var i = 0; i < data.length; i++) {
						options += '<option value="' + data[i].campagne_id + '">' + data[i].campagne_nom + ":" + data[i].experimentation_libelle + '</option>';
					};
					$("#campagne_id").html(options);
				});

		});

		$(".selection").change(function () {
			$("#search").submit();
		});
	});
</script>
<div class="row">
	<form class="form-horizontal" id="search" method="GET" action="traitList">
		<input type="hidden" name="isSearch" value="1">

		<div class="form-group">
			<label for="masse_eau_id" class="col-md-2 control-label">Masse d'eau : </label>
			<div class="col-md-3">
				<select class="selectcamp form-control" id="masse_eau_id" name="masse_eau_id" class="form-control">
					<option value="" {if $dataSearch.masse_eau_id=="" }selected{/if}></option>
					{section name=lst loop=$masse_eau}
					{strip}
					<option value="{$masse_eau[lst].masse_eau_id}" {if
						$masse_eau[lst].masse_eau_id==$dataSearch.masse_eau_id} selected{/if}>
						{$masse_eau[lst].masse_eau}
					</option>{/strip}
					{/section}
				</select>
			</div>

			<label for="annee" class="col-md-2 control-label">Année :</label>
			<div class="col-md-2">
				<select class="selectcamp form-control" id="annee" name="annee">
					<option value="" {if $dataSearch.annee=="" }selected{/if}></option>
					{foreach from=$annees item=annee}
					{strip}
					<option value="{$annee}" {if $annee==$dataSearch.annee} selected{/if}>
						{$annee}
					</option>{/strip}
					{/foreach}
				</select>
			</div>
			{if $rights.param == 1} 
			<label for="uid" class="col-md-1 control-label">Numéro du trait :</label>
			<div class="col-md-1">
				<input type="number" name="uid" id="uid" value="{$dataSearch.uid}" class="form-control">
			</div>
			{/if}
		</div>

		<div class="form-group">
			<label for="campagne_id" class="col-md-2 control-label">Campagne(s) de pêche :</label>
			<div class="col-md-4">
				<select id="campagne_id" name="campagne_id[]" multiple>
					{section name=lst loop=$campagne}
					<option value="{$campagne[lst].campagne_id}" {if $campagne[lst].is_checked==1}selected{/if}>
						{$campagne[lst].campagne_nom}:{$campagne[lst].experimentation_libelle}
					</option>
					{/section}
				</select>
			</div>
			<label for="saison" class="col-md-1 control-label">Saison : </label>
			<div class="col-md-2">
				<select class="selectcamp form-control" id="saison" name="saison">
					<option value="" {if $dataSearch.saison=="" }selected{/if}></option>
					{foreach from=$saisons item=saison}
					<option value="{$saison}" {if $saison==$dataSearch.saison}selected{/if}>
						{$saison}
					</option>
					{/foreach}
				</select>
			</div>
			<input class="btn btn-success col-md-1" type="submit" name="Rechercher..."
				value="Rechercher" autofocus>
		</div>

		{$csrf}
	</form>
</div>