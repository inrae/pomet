<script>
	$(document).ready(function () {
		$("#check").change(function () {
			$('.check').prop('checked', this.checked);
		});
	});
</script>

<h2>Import des données GPS depuis un fichier GPX</h2>
<a href="traitList"> <img src="display/images/list.png" height="25">
	Retour à la liste des traits
</a>
&nbsp;
<a href="traitDisplay?trait_id={$dataTrait.trait_id}"> <img src="display/images/detail.png" height="25"> Retour au
	détail du trait
</a>
<div class="row">
	<div class="col-md-6">

		{include file="traits/traitCartouche.tpl"}
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<fieldset>
			<legend>Fichier GPX à télécharger</legend>
			<form class="form-horizontal" action="importgpxSelectfile" enctype="multipart/form-data" method="post">
				<input type="hidden" name="trait_id" value="{$dataTrait.trait_id}">
				<div class="form-group">
					<label for="filename" class="control-label col-md-4">Fichier à télécharger (.gpx) :</label>
					<div class="col-md-8">
						<input type="file" class="form-control" id="filename" name="filename"
							accept="application/gpx+xml,text/xml,application/xml,application/octet-stream">
					</div>
				</div>
				<div class="center form-group">
					<button type="submit" class="btn btn-primary button-valid">Valider</button>
				</div>
				{$csrf}
			</form>
		</fieldset>
	</div>
</div>
{if !empty($traces) }
<div class="row">
	<div class="col-md-8">
		<form action="importgpxExec">
			<input type="hidden" name="trait_id" value="{$dataTrait.trait_id}">
			<table class="table table-bordered table-hover datatable display" id="traces">
				<thead>
					<tr>
						<th>Nom/N° de la trace</th>
						<th>Heure début</th>
						<th>Heure fin</th>
						<th>à importer ?&nbsp;
							<input type="checkbox" id="check" checked>
						</th>
				</thead>
				<tbody>
					{foreach $traces as $trace}
					<tr>
						<td class="center">{$trace.name}&nbsp;{$trace.number}</td>
						<td>{$trace.start}</td>
						<td>{$trace.end}</td>
						<td class="center">
							<input type="checkbox" class="check" name="tracenum[]" value="{$trace.number}" checked>
						</td>
					</tr>
					{/foreach}
				</tbody>
			</table>
			<div class="center form-group">
				<button type="submit" id="importExec" class="btn btn-danger">Déclencher l'importation</button>
			</div>
			{$csrf}
		</form>
	</div>
</div>
{/if}