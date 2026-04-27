<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions_layout.php';
require_once __DIR__ . '/../../includes/functions_pieces.php';
?>

<div class="contenu-fenetre">
    <div class="fenetre-header">
        <h2>Modifier une pièce</h2>
        <h3><?= $pce_id ?></h3>
        <button type="button" data-close>X</button>
    </div>

    <form action="" method="POST" id="form_mdf_pce">
        <input type="hidden" name="pce_id" id="pce_id" data-fill="id">

        <div class="bloc-formulaire">
            <div class="ligne-formulaire">
                <?php creerListePce($pdo, "etapes", "etp_libelle", "etp_id", "etapes_precedente", "etp_temoin_suppression"); ?>
            </div>

            <div class="ligne-formulaire">
                <?php creerListePce($pdo, "etapes", "etp_libelle", "etp_id", "etapes_en_cours", "etp_temoin_suppression"); ?>
            </div>

            <div class="ligne-formulaire">
                <?php creerListePce($pdo, "types_anomalie", "tano_libelle", "tano_id", "anomalie", "tano_temoin_suppression"); ?>
            </div>

            <div class="ligne-formulaire">
                <label for="pce_texte_anomalie">Détail anomalie</label>
                <input type="text" name="pce_texte_anomalie" id="pce_texte_anomalie" value="" data-pce_texte_anomalie data-fill="pce_texte_anomalie">
            </div>

            <div class="ligne-formulaire">
                <label for="pce_statut">Statut pièce</label>
                <select name="pce_statut" id="pce_statut" data-pce_statut data-fill="pce_statut">
                    <option value="actif">actif</option>
                    <option value="inactif">inactif</option>
                </select>
            </div>
        </div>

        <div class="actions-lot">
            <button type="submit" id="btn_vld_mdf_pce" name="btn_vld_mdf_pce">Modifier</button>
            <button type="button" id="btn_anl_mdf_pce" name="btn_anl_mdf_pce" data-close>Annuler</button>
        </div>
    </form>
</div>
