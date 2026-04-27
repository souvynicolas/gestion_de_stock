<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Modifier une anomalie</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_mdf_tano">
        <input type="hidden" name="tano_id" id="tano_id" data-fill="id">

        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <label for="anomalies_mdf">Anomalie</label>
                <input type="text" name="anomalies" id="anomalies_mdf" value="" data-fill="anomalies">
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_mdf_tano" name="btn_vld_mdf_tano">Modifier</button>
            <button type="button" id="btn_anl_mdf_tano" data-close>Annuler</button>
        </div>
    </form>
</div>