<h2>Liste des expérimentations</h2>
{if $rights["param"] == 1}
<a href="experimentationChange?experimentation_id=0">
<img src="display/images/new.png" height="25">&nbsp;
Nouvelle expérimentation...
</a>
{/if}
<table id="experimentationList" class="table table-bordered table-hover datatable display">
<thead>
<tr>
<th>Expérimentation</th>
<th>Contrôles<br>activés ?</th>
<th>Vitesse (m/mn)<br>(min/max)</th>
<th>Durée (mn)<br>(min/max)</th>
<th>Distance (m)<br>(min/max)</th>
<th>Écart max autorisé<br>(distance calculée/déclarée)</th>
</tr>
</thead><tbody>
{section name=lst loop=$data}
<tr>
<td>
{if $rights["param"] == 1}
<a href="experimentationChange?experimentation_id={$data[lst].experimentation_id}">
{$data[lst].experimentation_libelle}
</a>
{else}
{$data[lst].experimentation_libelle}
{/if}
</td>
<td class="center">
{if $data[lst].controle_enabled == 't'}oui{else}non{/if}</td>
<td class="center">{$data[lst].speed_min}/{$data[lst].speed_max}</td>
<td class="center">{$data[lst].duration_min}/{$data[lst].duration_max}</td>
<td class="center">{$data[lst].distance_min}/{$data[lst].distance_max}</td>
<td class="center">{$data[lst].max_allowed_distance_deviation}</td>
</tr>
{/section}
</tbody>
</table>
