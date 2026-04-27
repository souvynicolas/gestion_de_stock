<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Modifier une étape</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_mdf_etp">
        <input type="hidden" name="etp_id" id="etp_id" data-fill="id">

        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <label for="etapes_mdf">Étape</label>
                <input type="text" name="etapes" id="etapes_mdf" value="" data-fill="etapes">
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_mdf_etp" name="btn_vld_mdf_etp">Modifier</button>
            <button type="button" id="btn_anl_mdf_etp" data-close>Annuler</button>
        </div>
    </form>
</div>