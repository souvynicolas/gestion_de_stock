<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Supprimer une dimension</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_spr_dim">
        <input type="hidden" name="spr_dim_id" id="spr_dim_id" data-fill="id">
        <input type="hidden" name="btn_spr_confirm_dim" value="oui">

        <p>Êtes-vous sûr de vouloir supprimer cette dimension ?</p>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_spr_dim" name="btn_vld_spr_dim">Oui</button>
            <button type="button" id="btn_anl_spr_dim" data-close>Non</button>
        </div>
    </form>
</div>