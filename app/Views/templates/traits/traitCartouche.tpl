<div class="form-display">
<dl class="dl-horizontal">
<dt>Date du trait :</dt>
<dd>
{$dataTrait.madate}
{if strlen($dataTrait.station) > 0}
 - {$dataTrait.station}
 {/if}
 {if strlen($dataTrait.ordre)>0}
 - ordre de la pêche : {$dataTrait.ordre}
 {/if}
  </dd>
</dl>
 <dl class="dl-horizontal">
 <dt></dt>
 <dd>
 {$dataTrait.campagne_nom}
 </dd>
 </dl>
</div>