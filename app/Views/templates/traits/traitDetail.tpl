<div class="row">
    <div class="col-md-8">
        <div class="form-display">
            <dl class="dl-horizontal">
                <dt>Date :</dt>
                <dd>{$data.madate}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Engin utilisé :</dt>
                <dd>{$data.materiel_nom}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Station</dt>
                <dd>{$data.station}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt title="Ordre de pêche dans la journée">Ordre de pêche dans la journée :</dt>
                <dd>{$data.ordre}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Campagne :</dt>
                <dd>{$data.campagne_nom}:{$data.experimentation}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Commentaire :</dt>
                <dd>
                    <div class="textareaDisplay">{$data.commentaire}</div>
                </dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Trait validé :</dt>
                <dd>{if $data.validite == 1}
                    oui
                    {/if}
                </dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Durée du trait (mn) :</dt>
                <dd>{$data.duree}</dd>
            </dl>
            {if $data.code_agence == "GUY"}
            <dl class="dl-horizontal">
                <dt title="Hauteur d'eau pleine mer">Hauteur d'eau pleine mer :</dt>
                <dd>{$data.h_eau_pleine_mer}</dd>
            </dl>
            {else}
            <dl class="dl-horizontal">
                <dt title="Coefficient de marée">Coefficient de marée :</dt>
                <dd>{$data.maree}</dd>
            </dl>
            {/if}
            <dl class="dl-horizontal">
                <dt title="Profondeur de pêche (m)">Profondeur de pêche (m) :</dt>
                <dd>{$data.profondeur}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt title="Distance réelle chalutée (m)">Distance réelle chalutée (m) :</dt>
                <dd>{$data.distance_chalutee}</dd>
            </dl>

            <fieldset>
                <legend>Données physico-chimiques</legend>
                {if $data.temperature > 0}
                <dl class="dl-horizontal">
                    <dt>Température (°C) :</dt>
                    <dd>{$data.temperature}</dd>
                </dl>
                {/if}
                {if $data.oxygene > 0}
                <dl class="dl-horizontal">
                    <dt>Oxygène (% sat):</dt>
                    <dd>{$data.oxygene}</dd>
                </dl>
                {/if}
                <dl class="dl-horizontal">
                    <dt title="Salinité (PSU) (ou classe de salinité)">Salinité (PSU) (ou classe de salinité) :</dt>
                    <dd>{$data.salinite} {$data.salinite_libelle}</dd>
                </dl>
                {if $data.conductivite > 0}
                <dl class="dl-horizontal">
                    <dt>Conductivité (µS/cm) :</dt>
                    <dd>{$data.conductivite}</dd>
                </dl>
                {/if}
                {if $data.ph > 0}
                <dl class="dl-horizontal">
                    <dt>pH :</dt>
                    <dd>{$data.ph}</dd>
                </dl>
                {/if}
            </fieldset>
        </div>
    </div>
    <div class="col-md-4">
        <fieldset>
            <legend>Coordonnées GPS</legend>
            <div class="form-display">
                <dl class="dl-horizontal">
                    <dt>Point de début :</dt>
                    <dd>
                        Lat : {$data.pos_deb_lat} {$data.pos_deb_lat_dd}
                        <br>
                        Long : {$data.pos_deb_long} {$data.pos_deb_long_dd}
                        {if strlen($data.rgfg95_deb_x) > 0}
                        <br>Rgfg95 : {$data.rgfg95_deb_x} {$data.rgfg95_deb_y}
                        {/if}
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>Point de fin :</dt>
                    <dd>
                        Lat : {$data.pos_fin_lat} {$data.pos_fin_lat_dd}
                        <br>
                        Long : {$data.pos_fin_long} {$data.pos_fin_long_dd}
                        {if strlen($data.rgfg95_fin_x) > 0}
                        <br>Rgfg95 : {$data.rgfg95_fin_x} {$data.rgfg95_fin_y}
                        {/if}
                    </dd>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>Distance calculée :</dt>
                    <dd>{$distance_calculee}</dd>
                </dl>
                {if !empty($tracegps["trait_id"])}
                <dl class="dl-horizontal">
                    <dt>Début trace gps :</dt>
                    <dd>{$tracegps.trace_start}</dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>Fin trace gps :</dt>
                    <dd>{$tracegps.trace_end}</dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>Distance GPS calculée :</dt>
                    <dd>{$gps_trait_length}</dd>
                </dl>
                {/if}
            </div>

            <div class="row">
                <div class="center">
                    <!--  td style="width:400px"-->
                    {include file="traits/traitMap.tpl"}
                </div>
            </div>
        </fieldset>
    </div>
</div>