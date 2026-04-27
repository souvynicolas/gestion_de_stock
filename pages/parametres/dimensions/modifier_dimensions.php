<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Modifier une dimension</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_mdf_dim">
        <input type="hidden" name="dim_id" id="dim_id" data-fill="id">

        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <label for="dimensions_mdf">Dimension</label>
                <input type="number" name="dimensions" id="dimensions_mdf" value="" data-fill="dimensions">
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_mdf_dim" name="btn_vld_mdf_dim">Modifier</button>
            <button type="button" id="btn_anl_mdf_dim" data-close>Annuler</button>
        </div>
    </form>
</div>