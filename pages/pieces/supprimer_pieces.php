<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions_layout.php';
require_once __DIR__ . '/../../includes/functions_pieces.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Supprimer des pièces</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_spr_pce">
        <div id="spr_pce_ids_container"></div>
        <input type="hidden" name="btn_spr_confirm_pce" value="oui">

        <p>Êtes-vous sûr de vouloir supprimer les pièces sélectionnées ?</p>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_spr_pce" name="btn_vld_spr_pce">Oui</button>
            <button type="button" id="btn_anl_spr_pce" name="btn_anl_spr_pce" data-close>Non</button>
        </div>
    </form>
</div>