<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';
/*créer couleur*/
?>
<?php afficherErreurs($erreurs_couleurs ?? []); ?>
<div>
        <button type="button"  data-open="fenetre_creer_cou">Créer couleurs</button>
        <button type="button" id="btn_mdf_cou">Modifier couleurs</button>
        <button type="button" id="btn_spr_cou">Supprimer couleurs</button>
</div>

<?php afficherTableauParametres($couleurs, ''); ?>

<div id="fenetre_creer_cou" class="fenetre">
        <?php include 'creer_couleurs.php'; ?>
</div>
<div id="fenetre_mdf_cou" class="fenetre">
        <?php include 'modifier_couleurs.php'; ?>
</div>
<div id="fenetre_spr_cou" class="fenetre">
        <?php include 'supprimer_couleurs.php'; ?>
</div>




