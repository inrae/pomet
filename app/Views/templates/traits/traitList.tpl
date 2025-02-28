<div class="row">
    <div class="col-md-10">
        {include file="traits/traitSearch.tpl"}
    </div>

    {if $isSearch == 1}
    <div class="center col-md-2">
        <img src="display/images/csv.png" height="25">&nbsp; Exporter...<br>
        <a href="traitExport">la liste des traits</a><br>
        <a href="echantillonExport">la liste des échantillons</a><br>
        <a href="individuExport">la liste des individus</a>
    </div>
    {/if}
</div>
{if $isSearch == 1}
{if $rights.saisie == 1 || $rights.manage == 1}
<a href="traitChange?trait_id=0">
    <img src="display/images/new.png" height="25">
    Nouveau trait</a>
{/if}

<table id="traitList" class="table table-bordered table-hover datatable display" data-order='[[0,"asc"],[2,"asc"]]'>
    <thead>
        <tr>
            <th>Date</th>
            <th>Station</th>
            <th>Ordre</th>
            <th>Expérimentation</th>
            <th>Campagne</th>
            <th>Trait validé ?</th>
            <th>N° informatique</th>
        </tr>
    <tbody>
        {section name=lst loop=$data}
        <tr>
            <td>
                <a href="traitDisplay?trait_id={$data[lst].trait_id}">
                    {$data[lst].madate}
                </a>
            </td>
            <td>{$data[lst].station}</td>
            <td class="center">{$data[lst].ordre}</td>
            <td>{$data[lst].experimentation}</td>
            <td>{$data[lst].campagne_nom}</td>
            <td class="center">
                {if $data[lst].validite == 't'}
                oui
                {/if}
            </td>
            <td class="center">{$data[lst].trait_id_display}</td>
        </tr>
        {/section}
    </tbody>
</table>
{/if}