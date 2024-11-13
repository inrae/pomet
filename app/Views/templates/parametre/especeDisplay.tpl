<a href="especeList">Retour à la liste des espèces</a>
{if $rights.param == 1}
<br>
<a href="especeChange?espece_id={$data.espece_id}">
    <img src="display/images/edit.gif" height="20">Modifier...</a>
{/if}
<div class="row">
    <fieldset class="col-md-6">
        <legend>Détail de l'espèce </legend>
        <div class="form-display">
            <dl class="dl-horizontal">
                <dt> Nom latin :</dt>
                <dd>{$data.nom}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> Nom français :</dt>
                <dd>{$data.nom_fr}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> Auteur :</dt>
                <dd>{$data.auteur}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> Phylum :</dt>
                <dd>{$data.phylum}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> Subphylum :</dt>
                <dd>{$data.subphylum}</dd>
            </dl>

            <dl class="dl-horizontal">
                <dt> Classe :</dt>
                <dd>{$data.classe}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> Ordre :</dt>
                <dd>{$data.ordre}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> Famille :</dt>
                <dd>{$data.famille}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> Genre :</dt>
                <dd>{$data.genre}</dd>
            </dl>
            {if $rights.param == 1}
            <dl class="dl-horizontal">
                <dt> Code Perm Ifremer :</dt>
                <dd>{$data.code_perm_ifremer}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> Code Sandre :</dt>
                <dd>{$data.code_sandre}</dd>
            </dl>
            {/if}
        </div>
    </fieldset>
    {if !empty($size)}
    <fieldset class="col-md-6">
        <legend>Données morphologiques</legend>
        <div class="form-display">
            <dl class="dl-horizontal">
                <dt title="Longueur totale à maturité (cm)">L. totale à maturité (cm) :</dt>
                <dd>{$size.lt_maturity}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Age à maturité :</dt>
                <dd>{$size.age_maturity}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt title="Longueur totale maxi (cm)">L. totale maxi (cm) :</dt>
                <dd>{$size.lt_max}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Age maxi :</dt>
                <dd>{$size.age_max}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Statut IUCN :</dt>
                <dd>{$size.iucn}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt title="Nb années entre 2 reproductions">Nb années entre 2 repros :</dt>
                <dd>{$size.spawn_frequence}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Guilde de repro :</dt>
                <dd>{$size.repro_guild}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt title="Les parents prennent soin des juvéniles ?">Les parents prennent soin des juvéniles ?</dt>
                <dd>
                    {if isset($size.parent_care) && $size.parent_care == 0 && strlen($size.parent_care) > 0}non
                    {elseif $size.parent_care == 1}oui{/if}
                </dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Fécondité max :</dt>
                <dd>{$size.fecondity_max}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Taille des oeufs (mm) :</dt>
                <dd>{$size.egg_size}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Type de nage :</dt>
                <dd>{$size.swimming_name}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Résilience :</dt>
                <dd>{$size.resilience_name}</dd>
            </dl>
        </div>
    </fieldset>
    {/if}
    <fieldset class="col-md-6">
        <legend>Guilde</legend>
        <div class="form-display">
            <dl class="dl-horizontal">
                <dt title="Guilde écologique DCE 2007"> Guilde écol. DCE 2007 :</dt>
                <dd>{$guilde.guilde_ecologique_dce2007}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt title="Guilde trophique DCE 2007"> Guilde troph. DCE 2007 :</dt>
                <dd>{$guilde.guilde_trophique_dce2007}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> Guilde trophique lp :</dt>
                <dd>{$guilde.guilde_trophique_lp}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt title="Index trophique FishBase 2006"> Index troph. FishBase 2006 :</dt>
                <dd>{$guilde.index_trophique_fb2006}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> répartition DCE 2007 :</dt>
                <dd>{$guilde.repartition_dce2007}</dd>
            </dl>
        </div>
    </fieldset>
    <br>
    <fieldset class="col-md-6">
        <legend>Guilde WISER</legend>
        <div class="form-display">
            <dl class="dl-horizontal">
                <dt> Guilde écologique :</dt>
                <dd>{$wiser.Ecological_guild}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> Position :</dt>
                <dd>{$wiser.Position_guild}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt> Guilde trophique :</dt>
                <dd>{$wiser.Trophic_guild}</dd>
            </dl>
            <dl class="dl-horizontal">
                <dt title="Index trophique FishBase"> Index troph. FishBase :</dt>
                <dd>{$wiser.trophic_index_fishbase}</dd>
            </dl>
        </div>
    </fieldset>
    <br>


</div>