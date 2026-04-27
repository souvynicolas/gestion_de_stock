<?php
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../includes/functions_layout.php';
    require_once __DIR__ . '/../../includes/functions_pieces.php';
    require_once __DIR__ . '/../../includes/functions_historisations.php';
    require_once __DIR__ . '/../../includes/functions_mouvements.php';
    require_once __DIR__ . '/../../includes/functions_recherche.php';
    require_once __DIR__ . '/../../includes/layout.php';

    $pce_id = "";
$pce_article = "";
$pce_etape_precedente = "";
$pce_etape_en_cours = "";
$pce_anomalie = "";
$pce_date_debut = "";
$pce_date_fin = "";
/*créer piece*/
    $erreurs=[];
if($_SERVER["REQUEST_METHOD"] === "POST"  && isset($_POST["btn_vld_crt_pce"])) {
    $pce_quantite_pce=$_POST["quantite_pce"] ?? 0;     
    $pce_num_article=$_POST["articles"] ?? "";
    $pce_etp_en_cours = trim($_POST["etapes_en_cours"] ?? "");
    $pce_anomalie = trim($_POST["anomalie"]?? "");
    
    $pce_etp_en_cours=($pce_etp_en_cours === "") ? null : (int)$pce_etp_en_cours;
    $pce_anomalie=($pce_anomalie === "") ? null : (int)$pce_anomalie;
        

    
    $champs= ["quantite_pce", "articles","etapes_en_cours"];

    foreach ($champs as $champ) {
            if (!isset($_POST[$champ]) || trim($_POST[$champ]) === "") {
                $erreurs[] = "Le champ $champ est obligatoire.";
            }

        }if(empty($erreurs)) {
        for ($i = 0; $i < $pce_quantite_pce; $i++) {
            creerPiece($pdo, [$pce_num_article, $pce_etp_en_cours,$pce_anomalie]);

            $pce_id = (int)$pdo->lastInsertId();

            creerLigneHistorisation($pdo, [
                'piece_id' => $pce_id,
                'article_id' => (int)$pce_num_article,
                'etape_precedente' => null,
                'etape_en_cours' => $pce_etp_en_cours,
                'statut' => 'actif',
                'temoin_suppression' => 1,
                'date_suppression' => null,
                'utilisateur' => 'admin'
            ]);
        }
            
            header("Location: /gestion_de_stock/pages/pieces/pieces.php");
            exit;
        }
}

/*modifier piece*/
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btn_vld_mdf_pce"])) { 
    $pce_id=trim($_POST["pce_id"] ?? "");
    $pce_etp_mdf_en_cours=trim($_POST["etapes_en_cours"] ?? "");
    $pce_etp_mdf_precedente=trim($_POST["etapes_precedente"] ?? "");
    $pce_mdf_anomalie=trim($_POST["anomalie"]?? "");
    $pce_mdf_text_anomalie=trim($_POST["pce_texte_anomalie"]?? "");   
    $pce_mdf_statut=trim($_POST["pce_statut"]?? "");


    
    $champs= [
        "etapes_en_cours",
        "etapes_precedente",
        "pce_statut",
        
    ];

    if (!empty($_POST["pieces_check_ids"]) && count($_POST["pieces_check_ids"]) > 1) {
        $erreurs[] = "Une seule pièce peut être modifiée à la fois.";
        }

$pce_id = (int)$pce_id;
    $pce_etp_mdf_en_cours = ($pce_etp_mdf_en_cours === "") ? null : (int)$pce_etp_mdf_en_cours;
    $pce_etp_mdf_precedente = ($pce_etp_mdf_precedente === "") ? null : (int)$pce_etp_mdf_precedente;
    $pce_mdf_anomalie = ($pce_mdf_anomalie === "") ? null : (int)$pce_mdf_anomalie;

    if (
        $pce_etp_mdf_en_cours !== null &&
        $pce_etp_mdf_precedente !== null &&
        $pce_etp_mdf_en_cours === $pce_etp_mdf_precedente
    ) {
        $erreurs[] = "L'étape en cours et l'étape précédente doivent être différentes.";
    }

        foreach ($champs as $champ) {
            if (!isset($_POST[$champ]) || trim($_POST[$champ]) === "") {
                $erreurs[] = "Le champ $champ est obligatoire.";
            }

        }if(empty($erreurs)) {
            modifierPiece($pdo,[$pce_etp_mdf_en_cours,$pce_etp_mdf_precedente,$pce_mdf_anomalie,$pce_mdf_text_anomalie,$pce_mdf_statut,$pce_id]);

            
            $piece = recupInfoPceHist($pdo, $pce_id);
            if ($piece) {
                creerLigneHistorisation($pdo, [
                    'piece_id' => (int)$piece['pce_id'],
                    'article_id' => (int)$piece['pce_numero_article'],
                    'etape_precedente' => $piece['pce_etape_precedente'],
                    'etape_en_cours' => $piece['pce_etape_en_cours'],
                    'statut' => $piece['pce_statut'],
                    'temoin_suppression' => 0,
                    'type_anomalie' => $piece['pce_type_anomalie'] ?? null,
                    'texte_anomalie' => $piece['pce_texte_anomalie'] ?? null,
                    'date_suppression' => date('Y-m-d H:i:s'),
                    'utilisateur' => 'admin'
                ]);
            }
            
        header("Location: /gestion_de_stock/pages/pieces/pieces.php");
        exit;
        }
    }
/*supprimer piece*/


if ($_SERVER["REQUEST_METHOD"] === "POST"&& isset($_POST["btn_vld_spr_pce"])) {
    $pce_ids =$_POST["spr_pce_ids"] ?? [];
    $pce_btn_sup_confirm = trim($_POST["btn_spr_confirm_pce"] ?? "");

    if (!is_array($pce_ids) || empty($pce_ids)) {
        $erreurs[] = "Aucune pièce sélectionnée.";
    }

    if ($pce_btn_sup_confirm !== "oui") {
        $erreurs[] = "Suppression non confirmée.";
    }

    if (empty($erreurs)) {
        foreach ($pce_ids as $pce_id) {
            $pce_id = (int)$pce_id;

            if ($pce_id <= 0) {
                continue;
            }


        $piece = recupInfoPceHist($pdo, $pce_id);

        supprimerPiece($pdo, $pce_id);

            if ($piece) {
                creerLigneHistorisation($pdo, [
                    'piece_id' => (int)$piece['pce_id'],
                    'article_id' => (int)$piece['pce_numero_article'],
                    'etape_precedente' => $piece['pce_etape_precedente'],
                    'etape_en_cours' => $piece['pce_etape_en_cours'],
                    'statut' => $piece['pce_statut'],
                    'temoin_suppression' => 0,
                    'date_suppression' => date('Y-m-d H:i:s'),
                    'utilisateur' => 'admin'
                ]);
            }
        }
        header("Location: /gestion_de_stock/pages/pieces/pieces.php");
        exit;
    }
}

/*piece defaut*/
    $ouvrir_modal_defaut = false;
    $pieces_defaut = [];
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btn_valider_defaut"])) {
    $pieces_defaut_ids = $_POST["pieces_defaut_ids"] ?? [];
    $types_anomalie = $_POST["types_anomalie"] ?? [];
    $textes_anomalie = $_POST["textes_anomalie"] ?? [];

    if (empty($pieces_defaut_ids)) {
        $erreurs[] = "Aucune pièce à traiter en défaut.";
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

                    changerEtapePieceAvecDefaut(
                        $pdo,
                        $piece_id,
                        $etape_defaut,
                        $type_anomalie,
                        $texte_anomalie
                    );

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
                header("Location: pieces.php");
                exit;
            } catch (Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $erreurs[] = $e->getMessage();
            }
        }
    }

    $ouvrir_modal_defaut = true;
    $pieces_defaut = selectPiecesParIds($pdo, $pieces_defaut_ids);
}

/*mouvements*/

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action_etape"])) {
    $pieces_check_ids = $_POST["pieces_check_ids"] ?? [];
    $action_etape = trim($_POST["action_etape"] ?? "");



    if (empty($pieces_check_ids)) {
        $erreurs[] = "Aucune pièce sélectionnée.";
    }

/*si defaut*/
    if (empty($erreurs) && $action_etape === 'en defaut') {
        $pieces_defaut = selectPiecesParIds($pdo, $pieces_check_ids);
        $ouvrir_modal_defaut = true;
    }

    // autres mouvements classiques
    if (empty($erreurs) && $action_etape !== 'en defaut') {
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

                    $ancien_lot_id = recupLotActifPiece($pdo, $piece_id);

                    changerEtapePiece($pdo, $piece_id, $nouvelle_etape);

                    if ($ancien_lot_id !== null) {
                        marquerPieceTraiteeDansLot($pdo, $ancien_lot_id, $piece_id);
                        fermerLotSiVide($pdo, $ancien_lot_id);
                    }

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
                            'utilisateur' => null
                        ]);
                    }
                }

                $pdo->commit();
                header("Location: /gestion_de_stock/pages/pieces/pieces.php");
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

/*recherche piece*/

if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $pce_id= trim($_GET["id"]?? "");
            $pce_article = trim($_GET["article"] ?? "");
            $pce_etape_precedente = trim($_GET["etape_precedente"] ?? "");
            $pce_etape_en_cours = trim($_GET["etape_en_cours"] ?? "");
            $pce_anomalie = trim($_GET["anomalie"] ?? "");
            $pce_date_debut = str_replace('T', ' ',$_GET['date_debut'] ?? "");
            $pce_date_fin = str_replace('T', ' ',$_GET['date_fin'] ?? "");
        }


    $recuperer= recherchePiece( $pdo ,$pce_id, $pce_article, $pce_etape_precedente, $pce_etape_en_cours, $pce_anomalie,$pce_date_debut, $pce_date_fin );
    $erreurs = array_merge($erreurs, $recuperer['errors'] ?? []);
    $resultat = $recuperer['resultat'];


$pieces=selectAllPieces($pdo);


?>


<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../assets/style.css">
    <title>piece</title>
</head>
<body class= body_pieces>
    <?php afficherHeader(); ?>
    <?php afficherErreurs($erreurs); ?>
    <div class="zone-filtres">
    <form method="GET" class="form_recherche">

        <div class="champ">
            <label for="id">Numéro pièce :</label>
            <input type="text" name="id" value="<?= htmlspecialchars((string)$pce_id) ?>">
        </div>

        <div class="champ">
            <?php creerListePce($pdo, "articles", "art_libelle", "art_id", "article", "art_temoin_de_suppression"); ?>
        </div>

        <div class="champ">
            <?php creerListePce($pdo, "etapes", "etp_libelle", "etp_id", "etape_precedente", "etp_temoin_suppression"); ?>
        </div>

        <div class="champ">
            <?php creerListePce($pdo, "etapes", "etp_libelle", "etp_id", "etape_en_cours", "etp_temoin_suppression"); ?>
        </div>

        <div class="champ">
            <?php creerListePce($pdo, "types_anomalie", "tano_libelle", "tano_id", "anomalie", "tano_temoin_suppression"); ?>
        </div>

        <div class="champ">
            <label for="date_debut">Date de mise a jour de :</label>
            <input type="date" name="date_debut" value="<?= htmlspecialchars((string)$pce_date_debut) ?>">
        </div>

        <div class="champ">
            <label for="date_fin">à :</label>
            <input type="date" name="date_fin" value="<?= htmlspecialchars((string)$pce_date_fin) ?>">
        </div>

        <div class="actions-recherche">
            <button type="submit">Rechercher</button>
            <a  href="/gestion_de_stock/pages/pieces/pieces.php">Réinitialiser</a>
        </div>

    </form>
</div>
    <div>
        <button type="button"  data-open="fenetre_creer_pce">Créer pièce</button>
        <button type="button"  id="btn_mdf_pce">Modifier pièce</button>
        <button type="button" id="btn_spr_pce">Supprimer pièce</button>
    </div>

    <form action="" method="POST" id="form_lot_pieces">
        <div style="margin:15px 0; display:flex; gap:10px; flex-wrap:wrap;">
            <button type="submit" name="action_etape" value="en stock">En stock</button>
            <button type="submit" name="action_etape" value="en lavage">En lavage</button>
            <button type="submit" name="action_etape" value="en cours">En cours</button>
            <button type="submit" name="action_etape" value="en defaut">En defaut</button>    
        </div>
        <?php afficherTableauPiece($resultat, ['pce_', 'libelle']); ?>
    </form>

    <div id="fenetre_creer_pce" class="fenetre">
        <?php include 'creer_pieces.php'; ?>
    </div>
    <div id="fenetre_mdf_pce" class="fenetre">
        <?php include 'modifier_pieces.php'; ?>
    </div>
    <div id="fenetre_spr_pce" class="fenetre">
            <?php include 'supprimer_pieces.php'; ?>
    </div>
    <div id="fenetre_defaut" class="fenetre" style="<?= $ouvrir_modal_defaut ? 'display:flex;' : '' ?>">
        <div class="contenu-fenetre">
            <div class="fenetre-header">
                <h2>Déclarer des pièces en défaut</h2>
                <button type="button" data-close>X</button>
            </div>

            <form action="" method="POST">
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
                                    <?php afficherSelectTypeAnomalieParPiece($pdo, 'types_anomalie[' . (int)$piece['id'] . ']'); ?>
                                </td>
                                <td>
                                    <input type="text" name="textes_anomalie[<?= (int)$piece['id'] ?>]" value="">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="margin-top:15px; display:flex; gap:10px;">
                    <button type="submit" name="btn_valider_defaut">Valider défaut</button>
                    <button type="button" data-close>Annuler</button>
                </div>
            </form>
        </div>
    </div>



    <script src="../../assets/javascript.js"></script>
    </body>
    </html>


