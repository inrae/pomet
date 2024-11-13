<h2>Liste des espèces</h2>
{include file="parametre/especeSearch.tpl"}
{if $isSearch == 1}
{if $rights["param"] == 1}
<a href="especeChange?espece_id=0">
<img src="display/images/new.png" height="25">Nouvelle espèce...&nbsp;
</a>
{/if}
<a href="especeExport"><img src="display/images/csv.png" height="25">&nbsp;Exporter la liste au format CSV</a>
<table id="especeList" class="table table-bordered table-hover datatable display">
<thead>
<tr>
{if $rights.param == 1}
<th class="center"><img src="display/images/edit.gif" height="25"></th>
{/if}
<th>Nom latin</th>
<th>Nom français</th>
<th>Phylum</th>
<th>Subphylum</th>
<th>Classe</th>
<th>Ordre</th>
<th>Famille</th>
{if $rights.param == 1}
<th>Code Perm<br>Ifremer</th>
<th>Code Sandre</th>
{/if}
</tr>
</thead><tbody>
{section name=lst loop=$data}
<tr>
{if $rights.param == 1}
<td>
<a href="especeChange?espece_id={$data[lst].espece_id}">
<img src="display/images/edit.gif" height="25">
</a>
</td>
{/if}
<td>
<a href="especeDisplay?espece_id={$data[lst].espece_id}">
{$data[lst].nom} {$data[lst].auteur}
</a>
</td>
<td>{$data[lst].nom_fr}</td>
<td>{$data[lst].phylum}</td>
<td>{$data[lst].subphylum}</td>
<td>{$data[lst].classe}</td>
<td>{$data[lst].ordre}</td>
<td>{$data[lst].famille}</td>
{if $rights.param == 1}
<td class="center">{$data[lst].code_perm_ifremer}</td>
<td class="center">{$data[lst].code_sandre}</td>
{/if}
</tr>
{/section}
</tbody>
</table>
<br>
{/if}
