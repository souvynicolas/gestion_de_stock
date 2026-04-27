<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../includes/functions_layout.php';
    require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Modifier une couleur</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_mdf_cou">
        <input type="hidden" name="cou_id" id="cou_id" data-fill="id">

        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <label for="couleurs_mdf">Couleur</label>
                <input type="text" name="couleurs" id="couleurs_mdf" data-fill="couleurs">
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" name="btn_vld_mdf_cou">Modifier</button>
            <button type="button" data-close>Annuler</button>
        </div>
    </form>
</div>
