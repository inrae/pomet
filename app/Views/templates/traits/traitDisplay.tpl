<h2>Détail du trait</h2>
<a href="traitList">
<img src="display/images/list.png" height="25">
Retour à la liste</a>
<a href="#poissons"><img src="display/images/fish.png" height="25">Poissons capturés...</a>
{if $rights.saisie == 1 || $rights.manage == 1}
<a href="traitChange?trait_id=0">
<img src="display/images/new.png" height="25">
Nouveau trait</a>
{/if}
&nbsp;<a href="traitDisplay?trait_id={$data.trait_id}" title="rafraîchir la page">
<img src="display/images/refresh.png" height=20">
</a>
<div class="row">
<div class="col-md-12">

<fieldset class="col-md-12">
<legend>Données générales ({$data.trait_id_display})</legend>
{if $rights.manage == 1 || $rights.saisie == 1}
<a href="traitChange?trait_id={$data.trait_id}">
<img src="display/images/edit.gif" height="25">
Modifier le trait...</a>
&nbsp;
<a href="importgpxDisplay?trait_id={$data.trait_id}">
<img src="display/images/gpx.png" height="25">Importer la trace au format GPX
</a>
&nbsp;
<a href="importgpscsvDisplay?trait_id={$data.trait_id}">
<img src="display/images/csv.png" height="25"> Importer la trace au format CSV
</a>
{/if}
{include file="traits/traitDetail.tpl"}
</fieldset>
<fieldset class="col-md-8" id="poissons">
<legend>Poissons capturés</legend>
{include file="traits/echantillonList.tpl"}
</fieldset>

</div>
</div>
