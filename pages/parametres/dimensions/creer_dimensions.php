<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Créer une dimension</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_crt_dim">
        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <label for="dimensions">Dimension</label>
                <input type="number" name="dimensions" id="dimensions" value="">
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_crt_dim" name="btn_vld_crt_dim">Créer</button>
            <button type="button" id="btn_anl_crt_dim" data-close>Annuler</button>
        </div>
    </form>
</div>