<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../includes/functions_layout.php';
    require_once __DIR__ . '/../../../includes/functions_parametres.php';
/*créer anomalies*/




?>
<?php afficherErreurs($erreurs_anomalies ?? []); ?>
<div>
        <button type="button"  data-open="fenetre_creer_tano">Créer anomalies</button>
        <button type="button" id="btn_mdf_tano">Modifier anomalies</button>
        <button type="button" data-open="fenetre3"id="btn_spr_tano">Supprimer anomalies</button>
</div>
<?php afficherTableauParametres($anomalies, ''); ?>
<div id="fenetre_creer_tano" class="fenetre">
        <?php include 'creer_anomalies.php'; ?>
</div>
<div id="fenetre_mdf_tano" class="fenetre">
        <?php include 'modifier_anomalies.php'; ?>
</div>
<div id="fenetre_spr_tano" class="fenetre">
        <?php include 'supprimer_anomalies.php'; ?>
</div>




