<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Créer une matière</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_crt_mat">
        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <label for="matieres">Matière</label>
                <input type="text" name="matieres" id="matieres" value="">
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_crt_mat" name="btn_vld_crt_mat">Créer</button>
            <button type="button" id="btn_anl_crt_mat" data-close>Annuler</button>
        </div>
    </form>
</div>