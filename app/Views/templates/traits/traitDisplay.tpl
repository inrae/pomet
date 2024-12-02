<script>
    $(document).ready(function() { 
        /* Management of tabs */
		var myStorage = window.localStorage;
		var activeTab = "";
        try {
        activeTab = myStorage.getItem("traitDisplayTab");
        } catch (Exception) {
        }
		try {
			if (activeTab.length > 0) {
				$("#"+activeTab).tab('show');
			}
		} catch (Exception) { }
		/*$('.nav-tabs > li > a').hover(function() {
			$(this).tab('show');
 		});*/
		 $('.traitDisplayTab').on('shown.bs.tab', function () {
			myStorage.setItem("traitDisplayTab", $(this).attr("id"));
		});
    });
</script>

<h2>Détail du trait {$data.trait_id_display}</h2>
<a href="traitList">
    <img src="display/images/list.png" height="25">
    Retour à la liste</a>
{if $rights.saisie == 1 || $rights.manage == 1}
<a href="traitChange?trait_id=0">
    <img src="display/images/new.png" height="25">
    Nouveau trait</a>
{/if}
&nbsp;<a href="traitDisplay?trait_id={$data.trait_id}" title="rafraîchir la page">
    <img src="display/images/refresh.png" height=20">
</a>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="traitDisplayTab" role="tabdisplay">
            <li class="nav-item active">
                <a class="nav-link traitDisplayTab" id="tabdisplay-main" data-toggle="tab" role="tab"
                    aria-controls="navdisplay-main" aria-selected="true" href="#navdisplay-main">
                    {t}Données générales{/t}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link traitDisplayTab" id="tabdisplay-phy" href="#navdisplay-phy" data-toggle="tab"
                    role="tab" aria-controls="navdisplay-phy" aria-selected="false">
                    {t}Données physico-chimiques{/t}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link traitDisplayTab" id="tabdisplay-gps" href="#navdisplay-gps" data-toggle="tab"
                    role="tab" aria-controls="navdisplay-gps" aria-selected="false">
                    {t}Coordonnées GPS{/t}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link traitDisplayTab" id="tabdisplay-fish" href="#navdisplay-fish" data-toggle="tab"
                    role="tab" aria-controls="navdisplay-fish" aria-selected="false">
                    {t}Poissons capturés{/t}
                </a>
            </li>
        </ul>
        <div class="tab-content col-lg-12 form-horizontal" id="change-tabContent">
            <div class="tab-pane active in" id="navdisplay-main" role="tabpanel" aria-labelledby="tabdisplay-main">
                {if $rights.manage == 1 || $rights.saisie == 1}
                <div class="row">
                    <a href="traitChange?trait_id={$data.trait_id}">
                        <img src="display/images/edit.gif" height="25">
                        Modifier le trait...</a>
                </div>
                {/if}
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
                </div>
            </div>
            <div class="tab-pane fade" id="navdisplay-phy" role="tabpanel" aria-labelledby="tabdisplay-phy">
                {if $rights.manage == 1 || $rights.saisie == 1}
                <div class="row">
                    <a href="traitChange?trait_id={$data.trait_id}">
                        <img src="display/images/edit.gif" height="25">
                        Modifier le trait...</a>
                </div>
                {/if}
                <div class="form-display">
                    <dl class="dl-horizontal">
                        <dt>Température (°C) :</dt>
                        <dd>{$data.temperature}</dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>Oxygène (% sat):</dt>
                        <dd>{$data.oxygene}</dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt title="Salinité (PSU) (ou classe de salinité)">Salinité (PSU) (ou classe de salinité) :</dt>
                        <dd>{$data.salinite} {$data.salinite_libelle}</dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>Conductivité (µS/cm) :</dt>
                        <dd>{$data.conductivite}</dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>pH :</dt>
                        <dd>{$data.ph}</dd>
                    </dl>
                </div>
            </div>

            <div class="tab-pane fade" id="navdisplay-gps" role="tabpanel" aria-labelledby="tabdisplay-gps">
                {if $rights.manage == 1 || $rights.saisie == 1}
                <a href="traitChange?trait_id={$data.trait_id}">
                    <img src="display/images/edit.gif" height="25">
                    Modifier le trait...</a>
                &nbsp;
                <a href="importgpxDisplay?trait_id={$data.trait_id}">
                    <img src="display/images/gpx.png" height="25">Importer la trace au format GPX
                </a>
                &nbsp;
                <a href="importgpscsvDisplay?trait_id={$data.trait_id}">
                    <img src="display/images/csv.png" height="25"> Importer la trace au format CSV
                </a>
                {/if}
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
                        {include file="traits/traitMap.tpl"}
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="navdisplay-fish" role="tabpanel" aria-labelledby="tabdisplay-fish">
                {include file="traits/echantillonList.tpl"}
            </div>
        </div>
    </div>
</div>