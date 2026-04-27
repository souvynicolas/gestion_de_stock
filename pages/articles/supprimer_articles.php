<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions_layout.php';
require_once __DIR__ . '/../../includes/functions_articles.php';
require_once __DIR__ . '/../../includes/functions_pieces.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Supprimer un article</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_spr_art">
        <input type="hidden" name="spr_art_id" id="spr_art_id" data-fill="id">
        <input type="hidden" name="btn_spr_confirm_art" value="oui">

        <p>Êtes-vous sûr de vouloir supprimer cet article ?</p>

        <div class="actions-lot">
            <button type="submit" name="btn_vld_spr_art">Oui</button>
            <button type="button" data-close>Non</button>
        </div>
    </form>
</div>