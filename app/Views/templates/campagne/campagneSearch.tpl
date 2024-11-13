<script>
$(document).ready(function() {
	$(".selection").change(function () {
	//$("#search").submit();
	});
});
</script>
<div class="col-md-12">
<form class="form-horizontal" id="search" method="GET" action="campagneList">
<input type="hidden" name="isSearch" value="1">
<div class="form-group">
<label for="masse_eau_id" class="col-md-2 control-label">Masse d'eau : </label>
<div class="col-md-4">
<select class="selection form-control" id="masse_eau_id" name="masse_eau_id" class="form-control">
<option value = "" {if $dataSearch.masse_eau_id == ""}selected{/if}></option>
{section name=lst loop=$masse_eau}
{strip}
<option value="{$masse_eau[lst].masse_eau_id}"
{if $masse_eau[lst].masse_eau_id == $dataSearch.masse_eau_id} selected{/if}
>
{$masse_eau[lst].masse_eau}
</option>{/strip}
{/section}
</select>
</div>
</div>
<div class="form-group">
<label for="annee" class="col-md-2 control-label">Année :</label>
<div class="col-md-1">
<select class="selection form-control" id="annee" name="annee">
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
<label for="saison" class="col-md-1 control-label">Saison : </label>
<div class="col-md-1">
<select class="selection form-control" id="saison" name="saison">
<option value = "" {if $dataSearch.saison == ""}selected{/if}></option>
{foreach from=$saisons item=saison}
<option value="{$saison}" {if $saison == $dataSearch.saison}selected{/if}>
{$saison}
</option>
{/foreach}
</select>
</div>
<input class="btn btn-success col-md-2 col-md-offset-1" type="submit" name="Rechercher..." value="Rechercher" autofocus>
</div>

{$csrf}</form></div>
