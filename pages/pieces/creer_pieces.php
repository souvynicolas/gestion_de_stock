<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions_layout.php';
require_once __DIR__ . '/../../includes/functions_pieces.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Créer une pièce</h2>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_crt_pce">
        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <?php creerListePce($pdo, "articles", "art_libelle", "art_id", "articles", "art_temoin_de_suppression"); ?>
            </div>

            <div class="ligne-formulaire">
                <?php creerListePce($pdo, "etapes", "etp_libelle", "etp_id", "etapes_en_cours", "etp_temoin_suppression"); ?>
            </div>

            <div class="ligne-formulaire">
                <?php creerListePce($pdo, "types_anomalie", "tano_libelle", "tano_id", "anomalie", "tano_temoin_suppression"); ?>
            </div>

            <div class="ligne-formulaire">
                <label for="quantite_pce">Quantité :</label>
                <input
                    type="number"
                    name="quantite_pce"
                    id="quantite_pce"
                    value="1"
                    min="1"
                    data-quantite_pce
                    data-fill="quantite_pce"
                >
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_crt_pce" name="btn_vld_crt_pce">Créer</button>
            <button type="button" id="btn_anl_crt_pce" name="btn_anl_crt_pce" data-close>Annuler</button>
        </div>
    </form>
</div>