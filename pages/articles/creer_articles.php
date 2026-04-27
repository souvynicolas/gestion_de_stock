<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions_layout.php';
require_once __DIR__ . '/../../includes/functions_articles.php';
require_once __DIR__ . '/../../includes/functions_pieces.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Créer un article</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_crt_art">
        <div class="bloc-formulaire">

            <div class="ligne-formulaire">
                <label for="libelle">Nom article</label>
                <input type="text" name="libelle" id="libelle">
            </div>

            <div class="ligne-formulaire">
                <?php creerListePce($pdo, "dimensions", "dim_libelle", "dim_id", "largeur_id", "dim_temoin_de_suppression"); ?>
            </div>

            <div class="ligne-formulaire">
                <?php creerListePce($pdo, "dimensions", "dim_libelle", "dim_id", "longueur_id", "dim_temoin_de_suppression"); ?>
            </div>

            <div class="ligne-formulaire">
                <?php creerListePce($pdo, "couleurs", "cou_libelle", "cou_id", "couleur_id", "cou_temoin_de_suppression"); ?>
            </div>

            <div class="ligne-formulaire">
                <?php creerListePce($pdo, "matieres", "mat_libelle", "mat_id", "matiere_id", "mat_temoin_de_suppression"); ?>
            </div>

            <div class="ligne-formulaire">
                <label for="stock_total_mini">Stock total mini</label>
                <input type="number" name="stock_total_mini" id="stock_total_mini">
            </div>

            <div class="ligne-formulaire">
                <label for="stock_mini">Stock mini</label>
                <input type="number" name="stock_mini" id="stock_mini">
            </div>

        </div>

        <div class="actions-lot">
            <button type="submit" name="btn_vld_crt_art">Créer</button>
            <button type="button" data-close>Annuler</button>
        </div>
    </form>
</div>