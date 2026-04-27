<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>
<?php afficherErreurs($erreurs_matieres ?? []); ?>
<div>
        <button type="button"  data-open="fenetre_creer_mat">Créer matières</button>
        <button type="button" id="btn_mdf_mat">Modifier matières</button>
        <button type="button" id="btn_spr_mat">Supprimer matière</button>
</div>
<?php afficherTableauParametres($matieres, ''); ?>
<div id="fenetre_creer_mat" class="fenetre">
        <?php include 'creer_matieres.php'; ?>
</div>
<div id="fenetre_mdf_mat" class="fenetre">
        <?php include 'modifier_matieres.php'; ?>
</div>
<div id="fenetre_spr_mat" class="fenetre">
        <?php include 'supprimer_matieres.php'; ?>
</div>
