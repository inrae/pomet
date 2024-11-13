<script>
	$(document).ready(function() {
			$(".checkCampaignSelect").change( function() {
		$('.checkCampaign').prop('checked', this.checked);
	});
	});
</script>

<h2>Liste des campagnes</h2>
<div class="row">
{include file="campagne/campagneSearch.tpl"}
</div>
{if $isSearch == 1}
{if $rights["param"] == 1}
<form id="duplicateCampaign" method="post" action="campagneDuplicate">
	<div class="row">
	<div class="col-md-12">
<a href="campagneChange?campagne_id=0">
Nouvelle campagne...
</a>
<div class="row">
	<div class="col-md-3">
		<button id="createCampaigns" class="btn btn-primary" type="submit">{t}Dupliquer les campagnes sélectionnées pour l'année :{/t}</button>
		</div>
		<div class="col-md-1">
			<select class="form-control" id="anneeCopy" name="annee">
				<option value = "" {if $dataSearch.annee == ""}selected{/if}></option>
				{foreach from=$annees item=annee}
				{strip}
				<option value="{$annee}"
				{if $annee == $dataSearch.annee} selected{/if}
				>
				{$annee}
				</option>{/strip}
				{/foreach}
			</select>
		</div>
</div>
{/if}
<table id="campagneList" class="table table-bordered table-hover datatable display" data-order='[[3,"desc"],[4,"asc"], [2,"asc"]]' >
<thead>
<tr>
<th class="center">
	<input type="checkbox" id="checkCampaign2" class="checkCampaignSelect checkCampaign" >
</th>
<th>Nom</th>
<th>Masse d'eau</th>
<th>Année</th>
<th>Saison</th>
<th>Responsable</th>
<th>Expérimentation</th>
<th>Nbre de<br>traits</th>
<th>Actif en<br>saisie ?</th>
</tr>
</thead><tbody>
{section name=lst loop=$data}
<tr>
	<td class="center">
		<input type="checkbox" class="checkCampaign" name="campaigns[]" value="{$data[lst].campagne_id}" >
	</td>
<td>
<a href="campagneChange?campagne_id={$data[lst].campagne_id}">
{$data[lst].campagne_nom}
</a>
</td>
<td>{$data[lst].masse_eau}</td>
<td>{$data[lst].annee}</td>
<td>{$data[lst].saison}</td>
<td>{$data[lst].nom} {$data[lst].prenom}</td>
<td>{$data[lst].experimentation_libelle}</td>
<td class="center">{$data[lst].traits}</td>
<td class="center">
{if $data[lst].is_actif == 1}oui{/if}
</td>

</tr>
{/section}
</tbody>
</table>
</div>
</div>
{if $rights.param == 1}{$csrf}</form>{/if}
{/if}
