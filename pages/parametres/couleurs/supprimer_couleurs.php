<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../includes/functions_layout.php';
    require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>
<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Supprimer une couleur</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_spr_cou">
        <input type="hidden" name="spr_cou_id" id="spr_cou_id" data-fill="id">
        <input type="hidden" name="btn_spr_confirm_cou" value="oui">

        <p>Êtes-vous sûr de vouloir supprimer cette couleur ?</p>

        <div class="actions-lot">
            <button type="submit" name="btn_vld_spr_cou">Oui</button>
            <button type="button" data-close>Non</button>
        </div>
    </form>
</div>