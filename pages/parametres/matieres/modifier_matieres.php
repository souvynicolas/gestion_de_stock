<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions_layout.php';
require_once __DIR__ . '/../../../includes/functions_parametres.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Modifier une matière</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_mdf_mat">
        <input type="hidden" name="mat_id" id="mat_id" data-fill="id">

        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <label for="matieres_mdf">Matière</label>
                <input type="text" name="matieres" id="matieres_mdf" value="" data-fill="matieres">
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_mdf_mat" name="btn_vld_mdf_mat">Modifier</button>
            <button type="button" id="btn_anl_mdf_mat" data-close>Annuler</button>
        </div>
    </form>
</div>