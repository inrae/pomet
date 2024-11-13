<h2>Liste des matériels</h2>
{if $rights["param"] == 1}
<a href="materielChange?materiel_id=0">
<img src="display/images/new.png" height="25">&nbsp;
Nouvel engin de pêche...
</a>
{/if}
&nbsp;<a href="materielExport"><img src="display/images/csv.png" height="25">Exporter la liste au format CSV...</a>

<table id="materielList" class="table table-bordered table-hover datatable display">
<thead>
<tr>
<th>Nom</th>
<th>Type</th>
<th>Code</th>
<th>Expérimentation</th>
<th>Description</th>
</tr>
</thead><tbody>
{section name=lst loop=$data}
<tr>
<td>
{if $rights["param"] == 1}
<a href="materielChange?materiel_id={$data[lst].materiel_id}">
{$data[lst].materiel_nom}
</a>
{else}
{$data[lst].materiel_nom}
{/if}
</td>
<td class="center">{$data[lst].materiel_type}</td>
<td class="center">{$data[lst].materiel_code}</td>
<td class="center">{$data[lst].experimentation_libelle}</td>
<td class="textareaDisplay">{$data[lst].materiel_description}</td>
</tr>
{/section}
</tbody>
</table>