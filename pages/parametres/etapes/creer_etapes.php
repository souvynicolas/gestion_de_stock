<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Créer une étape</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_crt_etp">
        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <label for="etapes">Étape</label>
                <input type="text" name="etapes" id="etapes" value="">
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_crt_etp" name="btn_vld_crt_etp">Créer</button>
            <button type="button" id="btn_anl_crt_etp" data-close>Annuler</button>
        </div>
    </form>
</div>