<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../includes/functions_layout.php';
    require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Créer une couleur</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_crt_cou">
        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <label for="couleurs">Couleur</label>
                <input type="text" name="couleurs" id="couleurs">
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" name="btn_vld_crt_cou">Créer</button>
            <button type="button" data-close>Annuler</button>
        </div>
    </form>
</div>