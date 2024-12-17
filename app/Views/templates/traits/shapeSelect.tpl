<h2>Exportation des traits au format shape</h2>

<div class="row">
    <div class="col-md-6">
        <form class="form-horizontal" id="search" method="GET" action="traitShapeExec">
            <div class="row">
                <div class="form-group">
                    <label for="experimentation_id" class="col-md-2 control-label">Experimentation :</label>
                    <div class="col-md-4">
                        <select id="experimentation_id" name="experimentation_id" class="form-control">
                            {foreach $experimentations as $exp}
                            <option value="{$exp.experimentation_id}" {if
                                $exp.experimentation_id==$experimentation_id}selected{/if}>
                                {$exp.experimentation_libelle}
                            </option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="masseseaux" class="col-md-2 control-label">Masses d'eaux :</label>
                    <div class="col-md-4">
                        <select id="masseseaux" name="masseseaux[]" multiple class="form-control">
                            {foreach $masseseaux as $masseeau}
                            <option value="{$masseeau.masse_eau_id}">{$masseeau.masse_eau}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="yearfrom" class="col-md-2 control-label">
                        Années : de
                    </label>
                    <div class="col-md-2">
                        <select id="yearmin" name="yearmin" class="form-control">
                            {foreach $years as $year}
                            <option value="{$year}" {if $year==$yearmin}selected{/if}>{$year}</option>
                            {/foreach}
                        </select>
                    </div>
                    <label for="year" class="col-md-1 control-label">
                        à :
                    </label>
                    <div class="col-md-2">
                        <select id="yearmax" name="yearmax" class="form-control">
                            {foreach $years as $year}
                            <option value="{$year}" {if $year==$yearmax}selected{/if}>{$year}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group center">
                    <input class="btn btn-success" type="submit" value="Générer le fichier shp"
                        autofocus>
                </div>
            </div>
            {$csrf}
        </form>
    </div>
</div>