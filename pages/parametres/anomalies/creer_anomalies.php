<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Créer une anomalie</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_crt_tano">
        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <label for="anomalies">Anomalie</label>
                <input type="text" name="anomalies" id="anomalies" value="">
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_crt_tano" name="btn_vld_crt_tano">Créer</button>
            <button type="button" id="btn_anl_crt_tano" data-close>Annuler</button>
        </div>
    </form>
</div>