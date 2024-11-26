<h2>Liste des responsables de campagnes</h2>
{if $rights["param"] == 1}
<a href="personneChange?personne_id=0">
Nouveau responsable de campagne...
</a>
{/if}
<table id="personneList" class="table table-bordered table-hover datatable-searching display">
<thead>
<tr>
<th>Nom</th>
<th>Institut</th>
<th>Adresse</th>
<th>Téléphone</th>
<th>Mél</th>
</tr>
</thead><tbody>
{section name=lst loop=$data}
<tr>
<td>
{if $rights["param"] == 1}
<a href="personneChange?personne_id={$data[lst].personne_id}">
{$data[lst].nom} {$data[lst].prenom}
</a>
{else}
{$data[lst].nom} {$data[lst].prenom}
{/if}
</td>
<td>{$data[lst].institut}</td>
<td class="textareaDisplay">{$data[lst].adresse}</td>
<td>{$data[lst].telephone}</td>
<td>{$data[lst].email}</td>
</tr>
{/section}
</tbody>
</table>

