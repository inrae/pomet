{if $ind_id > 0}
<a href="echantillonChange?ech_id=0&ech_id={$dataEchan.ech_id}&trait_id={$dataTrait.trait_id}">
<img src="display/images/new.png" height="25">Nouveau poisson...
</a>
{/if}
{$masse=0}
{$nombre=0}
<table id="individuList" class="table table-bordered table-hover datatable display" >
<thead>
<tr>
<th><img src="display/images/edit.gif" height="25"></th>
<th>Longueur (mm)</th>
<th>Masse (g)</th>
</tr>
</thead>
<tbody>
{section name=lst loop=$individus}
<tr>
<td class="center {if $ind_id == $individus[lst].ind_id} backgroundGreen {/if} ">
<a href="echantillonChange?ech_id={$individus[lst].fk_ech_id}&ind_id={$individus[lst].ind_id}&trait_id={$dataTrait.trait_id}">
<img src="display/images/edit.gif" height="25">
</a>
</td>
<td class="right">{$individus[lst].longueur}</td>
<td class="right">{$individus[lst].poids}</td>
{$poids = $individus[lst].poids}
{if $poids > 0}
{$masse = $masse + $poids}
{/if}
{$nombre = $nombre + 1}
</tr>
{/section}
</tbody>
<tfoot>
<tr>
<td class="center">Total poissons<br>mesurés</td>
<td class="center">Nombre : <span id="totalPoisson">{$smarty.section.lst.total}</span></td>
<td class="right"><div id="totalMasse">{$masse}</div>
</tr>
</tfoot>
</table>
