<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions_layout.php';
require_once __DIR__ . '/../../includes/functions_pieces.php';
require_once __DIR__ . '/../../includes/functions_historisations.php';
require_once __DIR__ . '/../../includes/functions_mouvements.php';
require_once __DIR__ . '/../../includes/functions_accueil.php';
require_once __DIR__ . '/../../includes/layout.php';
require_once __DIR__ . '/../../classes/classe_pieces.php';

$class_pieces = new pieces($pdo);

$erreurs = [];
$ouvrir_modal_defaut = false;
$pieces_defaut = [];
$lot_defaut_id = null;


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btn_valider_defaut_accueil"])) {
    $lot_defaut_id = (int)($_POST["lot_defaut_id"] ?? 0);
    $pieces_defaut_ids = $_POST["pieces_defaut_ids"] ?? [];
    $types_anomalie = $_POST["types_anomalie"] ?? [];
    $textes_anomalie = $_POST["textes_anomalie"] ?? [];

    if ($lot_defaut_id <= 0) {
        $erreurs[] = "Lot défaut invalide.";
    }

    if (empty($pieces_defaut_ids)) {
        $erreurs[] = "Aucune pièce à traiter en défaut.";
    }

    if (empty($erreurs)) {
        $erreurs = array_merge($erreurs, validationAnomaliesModalDefaut($pdo, $types_anomalie));
    }

    if (empty($erreurs)) {
        $etape_defaut = recupEtapeParLibelle($pdo, 'en defaut');

        if ($etape_defaut === null) {
            $erreurs[] = "Étape défaut introuvable.";
        } else {
            try {
                $pdo->beginTransaction();

                foreach ($pieces_defaut_ids as $piece_id) {
                    $piece_id = (int)$piece_id;
                    $type_anomalie = $types_anomalie[$piece_id] ?? '';
                    $texte_anomalie = trim($textes_anomalie[$piece_id] ?? '');

                    $type_anomalie = ($type_anomalie === '') ? null : (int)$type_anomalie;
                    $texte_anomalie = ($texte_anomalie === '') ? null : $texte_anomalie;

                    $class_pieces->changerEtapePieceAvecDefaut(
                        $piece_id,
                        $etape_defaut,
                        $type_anomalie,
                        $texte_anomalie
                    );

                    /* la pièce sort du lot courant*/
                    marquerPieceTraiteeDansLot($pdo, $lot_defaut_id, $piece_id);

                    $piece_maj = $class_pieces->recupInfoPce($piece_id);

                    if ($piece_maj) {
                        creerLigneHistorisation($pdo, [
                            'piece_id' => (int)$piece_maj['pce_id'],
                            'article_id' => (int)$piece_maj['pce_numero_article'],
                            'etape_precedente' => $piece_maj['pce_etape_precedente'],
                            'etape_en_cours' => $piece_maj['pce_etape_en_cours'],
                            'statut' => $piece_maj['pce_statut'],
                            'temoin_suppression' => $piece_maj['pce_temoin_de_suppression'],
                            'date_suppression' => null,
                        ]);
                    }
                }

                fermerLotSiVide($pdo, $lot_defaut_id);

                $pdo->commit();
                header("Location: /gestion_de_stock/pages/accueil/accueil.php?open_lot=" . $lot_defaut_id);
                exit;
            } catch (Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $erreurs[] = $e->getMessage();
            }
        }
    }

    /*si erreur, on réouvre le modal défaut*/
    $ouvrir_modal_defaut = true;
    $pieces_defaut = $class_pieces->selectPiecesParIds($pieces_defaut_ids);
}
/*on traite uniquement les pièces cochées
en stock / en defaut => update seulement + sortie du lot
en cours / en lavage => update + sortie du lot + création nouveau lot
*/

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action_etape"])) {
    $lot_id = (int)($_POST["lot_id"] ?? 0);
    $pieces_check_ids = $_POST["pieces_check_ids"] ?? [];
    $action_etape = trim($_POST["action_etape"] ?? "");

    $erreurs = [];

    if ($lot_id <= 0) {
        $erreurs[] = "Lot invalide.";
    }

    if (empty($pieces_check_ids)) {
        $erreurs[] = "Aucune pièce sélectionnée.";
    }

    if (empty($erreurs)) {
        if ($action_etape === 'en defaut') {
            $ouvrir_modal_defaut = true;
            $lot_defaut_id = $lot_id;
            $pieces_defaut = $class_pieces->selectPiecesParIds($pieces_check_ids);
        } else {
            $nouvelle_etape = recupEtapeParLibelle($pdo, $action_etape);

        if ($nouvelle_etape === null) {
                $erreurs[] = "Étape introuvable.";
                } else {
            try {
                $pdo->beginTransaction();

                /*seulement en cours / en lavage créent un nouveau lot*/
                $creer_nouveau_lot = in_array(
                    $action_etape,
                    ['en cours', 'en lavage'],
                    true
                );

                $nouveau_lot_id = null;

                if ($creer_nouveau_lot) {
                    $lot_etape_libelle = recupLibelleEtape($pdo, $nouvelle_etape);
                    $nouveau_lot_id = creerLot($pdo, $nouvelle_etape, $lot_etape_libelle);
                }

                foreach ($pieces_check_ids as $piece_id) {
                    $piece_id = (int)$piece_id;

                    /* update de la pièce*/
                    $class_pieces->changerEtapePiece($piece_id, $nouvelle_etape);

                    /* la pièce sort du lot actuel*/
                    marquerPieceTraiteeDansLot($pdo, $lot_id, $piece_id);

                    /* si nouvelle étape = en cours / en lavage => nouveau lot*/
                    if ($creer_nouveau_lot && $nouveau_lot_id !== null) {
                        ajouterPieceDansLot($pdo, $nouveau_lot_id, $piece_id);
                    }

                /* historisation*/
                    $piece_maj = $class_pieces->recupInfoPce($piece_id);

                    if ($piece_maj) {
                        creerLigneHistorisation($pdo, [
                            'piece_id' => (int)$piece_maj['pce_id'],
                            'article_id' => (int)$piece_maj['pce_numero_article'],
                            'etape_precedente' =>$piece_maj['pce_etape_precedente'],
                            'etape_en_cours' =>$piece_maj['pce_etape_en_cours'],
                            'statut' => $piece_maj['pce_statut'],
                            'temoin_suppression' => $piece_maj['pce_temoin_de_suppression'],
                            'date_suppression' => null,
                        ]);
                    }
                }

                /* si plus aucune pièce dans le lot => lot terminé*/
                fermerLotSiVide($pdo, $lot_id);

                $pdo->commit();
                header("Location: /gestion_de_stock/pages/accueil/accueil.php?open_lot=" . $lot_id);
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
}



$lots = selectLotsOuverts($pdo);

/*on prépare les pièces de chaque lot avant le HTML*/
$lots_avec_pieces = [];

foreach ($lots as $lot) {
    $lot['pieces'] = selectPiecesParLot($pdo, (int)$lot['lot_id']);
    $lots_avec_pieces[] = $lot;
}
?> 

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="../../assets/style.css">


</head>
<body data-open-lot="<?= isset($_GET['open_lot']) ? (int)$_GET['open_lot'] : '' ?>">
<?php afficherHeader(); ?>

    <h1>Accueil</h1>

    <?php afficherErreurs($erreurs); ?>

<?php afficherCartesLots($lots_avec_pieces); ?>
<?php afficherModalsLots($lots_avec_pieces); ?>
<div id="fenetre_defaut_accueil" class="fenetre-defaut" style="<?= $ouvrir_modal_defaut ? 'display:flex;' : '' ?>">
        <div class="contenu-fenetre">
            <div class="fenetre-header">
                <h2>Déclarer défaut </h2>
                <button type="button" data-close-defaut>X</button>
            </div>

            <form action="" method="POST">
                <input type="hidden" name="lot_defaut_id" value="<?= (int)$lot_defaut_id ?>">

                <table class="tableau">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Article</th>
                            <th>Étape en cours</th>
                            <th>Étape précédente</th>
                            <th>Largeur</th>
                            <th>Longueur</th>
                            <th>Couleur</th>
                            <th>Type anomalie</th>
                            <th>Détail anomalie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pieces_defaut as $piece): ?>
                            <tr>
                                <td>
                                    <?= (int)$piece['id'] ?>
                                    <input type="hidden" name="pieces_defaut_ids[]" value="<?= (int)$piece['id'] ?>">
                                </td>
                                <td><?= htmlspecialchars($piece['article'] ?? '') ?></td>
                                <td><?= htmlspecialchars($piece['etape_en_cours'] ?? '') ?></td>
                                <td><?= htmlspecialchars($piece['etape_precedente'] ?? '') ?></td>
                                <td><?= htmlspecialchars($piece['largeur'] ?? '') ?></td>
                                <td><?= htmlspecialchars($piece['longueur'] ?? '') ?></td>
                                <td><?= htmlspecialchars($piece['couleur'] ?? '') ?></td>
                                <td>
                                    <?php $class_pieces->afficherSelectTypeAnomalieParPiece('types_anomalie[' . (int)$piece['id'] . ']'); ?>
                                </td>
                                <td>
                                    <input type="text" name="textes_anomalie[<?= (int)$piece['id'] ?>]" value="">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="margin-top:15px; display:flex; gap:10px;">
                    <button type="submit" name="btn_valider_defaut_accueil">Valider tout</button>
                    <button type="button" data-close-defaut>Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/javascript.js"></script>
</body>
</html>