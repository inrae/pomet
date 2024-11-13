<h2>Import des données GPS depuis un fichier GPX</h2>
<a href="traitList"> <img src="display/images/list.png" height="25">
	Retour à la liste des traits
</a>
&nbsp;
<a href="traitDisplay?trait_id={$dataTrait.trait_id}"> <img
	src="display/images/detail.png" height="25"
> Retour au détail du trait
</a>
<div class="row">
<div class="col-md-6">

{include file="traits/traitCartouche.tpl"}
</div>
</div>
<div class="row">
<div class="col-md-6">
<fieldset>
<legend>Fichier CSV à télécharger</legend>
<div class="col-md-12 tablecomment">
  Le fichier doit contenir impérativement les colonnes suivantes :
  <br>
  <ul>
    <li>time : la date-heure du point gps, sous une forme de type 2021-06-01T13:09:46.000Z ou équivalente</li>
    <li>lat : la latitude, sous forme numérique</li>
    <li>lon : la longitude, sous forme numérique</li>
  </ul>
  L'ordre des colonnes importe peu.
</div>
<form  class="form-horizontal" action="importgpscsvExec" enctype="multipart/form-data" method="post">
<input type="hidden" name="trait_id" value="{$dataTrait.trait_id}">
<div class="form-group">
<label for="filename"  class="control-label col-md-4">Fichier à télécharger (.csv) :</label>
<div class="col-md-8">
<input type="file" class="form-control" id="filename" name="filename" accept="text/csv">
</div>
</div>
<div class="form-group">
  <label for="separator" class="control-label col-md-4">Séparateur de champs :</label>
  <div class="col-md-8">
    <select id="separator" name="separator" class="form-control">
      <option value="," selected>Virgule</option>
      <option value="tab">Tabulation</option>
      <option value=";">Point-virgule</option>
    </select>
  </div>
</div>
<div class="center form-group">
<button type="submit" class="btn btn-primary button-valid">Valider</button>
</div>
{$csrf}</form>
</fieldset>
</div>
</div>
