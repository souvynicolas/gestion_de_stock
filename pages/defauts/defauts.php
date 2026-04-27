<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions_layout.php';
require_once __DIR__ . '/../../includes/functions_pieces.php';
require_once __DIR__ . '/../../includes/functions_mouvements.php';
require_once __DIR__ . '/../../includes/functions_defaut.php';
require_once __DIR__ . '/../../includes/layout.php';
require_once __DIR__ . '/../../includes/functions_historisations.php';

$erreurs = [];

/* mouvements depuis la page défaut */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action_etape"])) {
    $pieces_check_ids = $_POST["pieces_check_ids"] ?? [];
    $action_etape = trim($_POST["action_etape"] ?? "");

    if (empty($pieces_check_ids)) {
        $erreurs[] = "Aucune pièce sélectionnée.";
    }

    if (empty($erreurs)) {
        $nouvelle_etape = recupEtapeParLibelle($pdo, $action_etape);

        if ($nouvelle_etape === null) {
            $erreurs[] = "Étape introuvable.";
        } else {
            try {
                $pdo->beginTransaction();

                $creer_lot = in_array($action_etape, ['en cours', 'en lavage'], true);
                $lot_id = null;

                if ($creer_lot) {
                    $lot_etape_libelle = recupLibelleEtape($pdo, $nouvelle_etape);
                    $lot_id = creerLot($pdo, $nouvelle_etape, $lot_etape_libelle);
                }

                foreach ($pieces_check_ids as $piece_id) {
                    $piece_id = (int)$piece_id;

                    $piece_avant = recupInfoPce($pdo, $piece_id);
                    if (!$piece_avant) {
                        continue;
                    }

                    changerEtapePiece($pdo, $piece_id, $nouvelle_etape);

                    if ($creer_lot && $lot_id !== null) {
                        ajouterPieceDansLot($pdo, $lot_id, $piece_id);
                    }

                    $piece_maj = recupInfoPce($pdo, $piece_id);

                    if ($piece_maj) {
                        creerLigneHistorisation($pdo, [
                            'piece_id' => (int)$piece_maj['pce_id'],
                            'article_id' => (int)$piece_maj['pce_numero_article'],
                            'etape_precedente' => $piece_maj['pce_etape_precedente'],
                            'etape_en_cours' => $piece_maj['pce_etape_en_cours'],
                            'statut' => $piece_maj['pce_statut'],
                            'temoin_suppression' => $piece_maj['pce_temoin_de_suppression'],
                            'type_anomalie' => $piece_maj['pce_type_anomalie'] ?? null,
                            'texte_anomalie' => $piece_maj['pce_texte_anomalie'] ?? null,
                            'date_suppression' => null,
                            'utilisateur' => $piece_maj['pce_utilisateur'] ?? null
                        ]);
                    }
                }

                $pdo->commit();
                header("Location: defauts.php");
                exit;
            } catch (Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $erreurs[] = $e->getMessage();
            }
        }
    }
}

$defaut = selectAllPiecesDefaut($pdo);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Defaut</title>
    <link rel="stylesheet" type="text/css" href="../../assets/style.css">
</head>
<body>
    <?php afficherHeader(); ?>
    <h1>Defaut</h1>
    <?php afficherErreurs($erreurs); ?>
    <form action="" method="POST" id="form_lot_pieces">
        <div style="margin:15px 0; display:flex; gap:10px; flex-wrap:wrap;">
            <button type="submit" name="action_etape" value="en stock">En stock</button>
            <button type="submit" name="action_etape" value="en lavage">En lavage</button>
            <button type="submit" name="action_etape" value="en cours">En cours</button>   
        </div>
        <?php afficherTableauDefaut($defaut, ['pce_', 'libelle']); ?>
    </form>

     <script src="../../assets/javascript.js"></script>
</body>
</html>