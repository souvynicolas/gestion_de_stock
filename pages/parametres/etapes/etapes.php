<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';

?>
<?php afficherErreurs($erreurs_etapes ?? []); ?>
<div>
        <button type="button"  data-open="fenetre_creer_etp">Créer etapes</button>
        <button type="button" id="btn_mdf_etp">Modifier etapes</button>
        <button type="button" data-open="fenetre3"id="btn_spr_etp">Supprimer etapes</button>
</div>
<?php afficherTableauParametres($etapes, ''); ?>
<div id="fenetre_creer_etp" class="fenetre">
        <?php include 'creer_etapes.php'; ?>
</div>
<div id="fenetre_mdf_etp" class="fenetre">
        <?php include 'modifier_etapes.php'; ?>
</div>
<div id="fenetre_spr_etp" class="fenetre">
        <?php include 'supprimer_etapes.php'; ?>
</div>

