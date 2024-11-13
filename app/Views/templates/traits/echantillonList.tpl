{if $rights.manage == 1 || $rights.saisie == 1}
<a href="echantillonChange?ech_id=0&trait_id={$data.trait_id}">
<img src="display/images/new.png" height="25">Nouvelle espèce...
</a>
{/if}
<table id="echantillonListe"  class="table table-bordered table-hover datatable display" data-order='[[1,"asc"]]'>
<thead>
<tr>
<th class="center"><img src="display/images/edit.gif" height="25"></th>
<th>Espèce</th>
<th>Nombre de poissons capturés</th>
<th>Masse totale (g)</th>
</tr>
</thead>
<tbody>
{section name=lst loop=$dataEchantillon}
<tr>
<td class="center">
<a href="echantillonChange?ech_id={$dataEchantillon[lst].ech_id}&trait_id={$data.trait_id}">
{$dataEchantillon[lst].ech_id}<img src="display/images/edit.gif" height="25">
</a>
</td>
<td>
{$dataEchantillon[lst].nom} ({$dataEchantillon[lst].nom_fr})
</td>
<td class="center">{$dataEchantillon[lst].nt}</td>
<td class="right">{$dataEchantillon[lst].pt}</td>
</tr>
{/section}
</tbody>
</table>
