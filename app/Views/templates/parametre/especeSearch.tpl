<script>
	$(document).ready(function () {

		/*
		 * Fonction de recherche des select lies au parent
		 */
		function fieldSearch(id, parentId, valeur) {
			console.log("id:" + id);
			var subid = "";
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
			$.getJSON(url, { "value": valeur, "field": subid, "parentField": parentId }, function (data) {
				options = '<option value="" selected></option>';
				for (var i = 0; i < data.length; i++) {
					options += '<option value="' + data[i].field + '">' + data[i].field + '</option>';
				};
				if (subid.length > 0) {
					$("#" + subid).html(options);
				}
			});

			if (subid.length > 0) {
				fieldSearch(subid, parentId, valeur);
			}

		}

		$(".selection").change(function () {
			var currentId = $(this).attr('id');
			var valeur = $(this).val();
			fieldSearch(currentId, currentId, valeur);
		});
	});
</script>
<div class="row">
	<form class="form-horizontal" id="search" method="GET" action="especeList">
		<input type="hidden" name="isSearch" value="1">
		<div class="form-group">
			<label for="nom" class="col-md-4 control-label">
				Espèce recherchée (nom latin ou français) ou code Sandre : </label>
			<div class="col-md-4">
				<input id="nom" name="nom" class="col-md-4 form-control" value="{$dataSearch.nom}">
			</div>
		</div>
		<div class="row">
			<label for="phylum" class="col-md-1 control-label">Phylum : </label>
			<div class="col-md-2">
				<select class="selection form-control" id="phylum" name="phylum">
					<option value="" {if $dataSearch.phylum=="" }selected{/if}>
						{section name=lst loop=$phylum}
						{strip}
					<option value="{$phylum[lst].field}" {if $phylum[lst].field==$dataSearch.phylum} selected{/if}>
						{$phylum[lst].field}
					</option>{/strip}
					{/section}
				</select>
			</div>

			<label for="subphylum" class="col-md-1 control-label">Subphylum : </label>
			<div class="col-md-2">
				<select class="selection form-control" id="subphylum" name="subphylum">
					<option value="" {if $dataSearch.subphylum=="" }selected{/if}>
						{section name=lst loop=$subphylum}
						{strip}
					<option value="{$subphylum[lst].field}" {if $subphylum[lst].field==$dataSearch.subphylum}
						selected{/if}>
						{$subphylum[lst].field}
					</option>{/strip}
					{/section}
				</select>
			</div>

			<label for="classe" class="col-md-1 control-label">Classe :</label>
			<div class="col-md-2">
				<select class="selection form-control" id="classe" name="classe">
					<option value="" {if $dataSearch.classe=="" }selected{/if}>
						{section name=lst loop=$classe}
						{strip}
					<option value="{$classe[lst].field}" {if $classe[lst].field==$dataSearch.classe} selected{/if}>
						{$classe[lst].field}
					</option>{/strip}
					{/section}
				</select>
			</div>
		</div>

		<div class="row">
			<label for="ordre" class="col-md-1 control-label">Ordre :</label>
			<div class="col-md-2">
				<select class="selection form-control" id="ordre" name="ordre">
					<option value="" {if $dataSearch.ordre=="" }selected{/if}>
						{section name=lst loop=$ordre}
						{strip}
					<option value="{$ordre[lst].field}" {if $ordre[lst].field==$dataSearch.ordre} selected{/if}>
						{$ordre[lst].field}
					</option>{/strip}
					{/section}
				</select>
			</div>
			<label for="famille" class="col-md-1 control-label">Famille :</label>
			<div class="col-md-2">
				<select class="selection form-control" id="famille" name="famille">
					<option value="" {if $dataSearch.famille=="" }selected{/if}>
						{section name=lst loop=$famille}
						{strip}
					<option value="{$famille[lst].field}" {if $famille[lst].field==$dataSearch.famille} selected{/if}>
						{$famille[lst].field}
					</option>{/strip}
					{/section}
				</select>
			</div>

			<label for="genre" class="col-md-1 control-label">Genre :</label>
			<div class="col-md-2">
				<select class="selection form-control" id="genre" name="genre">
					<option value="" {if $dataSearch.genre=="" }selected{/if}>
						{section name=lst loop=$genre}
						{strip}
					<option value="{$genre[lst].field}" {if $genre[lst].field==$dataSearch.genre} selected{/if}>
						{$genre[lst].field}
					</option>{/strip}
					{/section}
				</select>
			</div>
			<input class="btn btn-success" type="submit" name="Rechercher..." value="Rechercher" autofocus>
		</div>
		{$csrf}
	</form>
</div>