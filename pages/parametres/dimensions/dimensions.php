<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';

?>
<?php afficherErreurs($erreurs_dimensions ?? []); ?>

<div>
        <button type="button"  data-open="fenetre_creer_dim">Créer dimensions</button>
        <button type="button" id="btn_mdf_dim">Modifier dimensions</button>
        <button type="button" id="btn_spr_dim">Supprimer dimensions</button>
</div>
<?php afficherTableauParametres($dimensions, ''); ?>
<div id="fenetre_creer_dim" class="fenetre">
        <?php include 'creer_dimensions.php'; ?>
</div>
<div id="fenetre_mdf_dim" class="fenetre">
        <?php include 'modifier_dimensions.php'; ?>
</div>
<div id="fenetre_spr_dim" class="fenetre">
        <?php include 'supprimer_dimensions.php'; ?>
</div>

