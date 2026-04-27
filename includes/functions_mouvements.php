<?php
function creerLot(PDO $pdo, int $lot_etape_destination, string $lot_etape_libelle): int
{
    $sql = "INSERT INTO lots (lot_etape_destination,lot_etape_libelle,lot_statut)
     VALUES (?, ?, 'actif')";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([ $lot_etape_destination,$lot_etape_libelle]);

    return (int)$pdo->lastInsertId();
}

function ajouterPieceDansLot(PDO $pdo, int $lot_id, int $pce_id): bool
{
    $sql = "INSERT INTO lot_pieces (ltp_lot_id, ltp_pce_id, ltp_statut) 
    VALUES (?, ?, 'dans_lot')";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$lot_id, $pce_id]);
}

function selectLotsOuverts(PDO $pdo): array
{
    $sql = "SELECT 
                l.lot_id,
                l.lot_etape_destination,
                l.lot_etape_libelle,
                l.lot_date_creation,
                COUNT(lp.ltp_id) AS nb_pieces
            FROM lots l
            INNER JOIN lot_pieces lp ON lp.ltp_lot_id = l.lot_id
            WHERE l.lot_statut = 'actif'
              AND lp.ltp_statut = 'dans_lot'
            GROUP BY l.lot_id, l.lot_etape_destination, l.lot_etape_libelle, l.lot_date_creation
            ORDER BY l.lot_date_creation ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function selectPiecesParLot(PDO $pdo, int $lot_id): array
{
    $sql = "SELECT  
                p.pce_id AS id,a.art_libelle AS article,e1.etp_libelle AS etape_en_cours,
                d1.dim_libelle AS largeur,d2.dim_libelle AS longueur,
                c.cou_libelle AS couleur
            FROM lot_pieces lp
            INNER JOIN pieces_de_linge p ON p.pce_id = lp.ltp_pce_id
            LEFT JOIN articles a ON p.pce_numero_article = a.art_id
            LEFT JOIN dimensions d1 ON a.art_largeur = d1.dim_id
            LEFT JOIN dimensions d2 ON a.art_longueur = d2.dim_id
            LEFT JOIN couleurs c ON a.art_couleur = c.cou_id
            LEFT JOIN etapes e1 ON p.pce_etape_en_cours = e1.etp_id
            LEFT JOIN etapes e2 ON p.pce_etape_precedente = e2.etp_id
            WHERE lp.ltp_lot_id = ?
            AND lp.ltp_statut = 'dans_lot'
            AND p.pce_temoin_de_suppression = 1
            ORDER BY p.pce_id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lot_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function marquerPieceTraiteeDansLot(PDO $pdo, int $lot_id, int $pce_id): bool
{
    $sql = "UPDATE lot_pieces
            SET ltp_statut = 'traite',ltp_date_traitement = NOW()
            WHERE ltp_lot_id = ?
            AND ltp_pce_id = ?
            AND ltp_statut = 'dans_lot'";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$lot_id, $pce_id]);
}

function compterPiecesRestantesLot(PDO $pdo, int $lot_id): int
{
    $sql = "SELECT COUNT(*)
            FROM lot_pieces
            WHERE ltp_lot_id = ?
            AND ltp_statut = 'dans_lot'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lot_id]);

    return (int)$stmt->fetchColumn();
}

function fermerLotSiVide(PDO $pdo, int $lot_id): bool
{
    if (compterPiecesRestantesLot($pdo, $lot_id) > 0) {
        return false;
    }

    $sql = "UPDATE lots
            SET lot_statut = 'inactif',
                lot_date_mise_a_jour = NOW()
            WHERE lot_id = ?";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$lot_id]);
}

function recupEtapeParLibelle(PDO $pdo, string $libelle): ?int
{
    $sql = "SELECT etp_id FROM etapes WHERE etp_libelle = ?
            AND etp_temoin_suppression = 1
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$libelle]);

    $id = $stmt->fetchColumn();
    return $id !== false ? (int)$id : null;
}

function recupLibelleEtape(PDO $pdo, int $etape_id): ?string
{
    $sql = "SELECT etp_libelle FROM etapes WHERE etp_id = ?
            AND etp_temoin_suppression = 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$etape_id]);

    $libelle = $stmt->fetchColumn();
    return $libelle !== false ? $libelle : null;
}

function MouvementPieces(PDO $pdo, array $pieces_check_ids, string $action_etape): array
{
    $erreurs = [];

    if (empty($pieces_check_ids)) {
        $erreurs[] = "Aucune pièce sélectionnée.";
        return $erreurs;
    }

    $nouvelle_etape = recupEtapeParLibelle($pdo, $action_etape);

    if ($nouvelle_etape === null) {
        $erreurs[] = "Étape introuvable.";
        return $erreurs;
    }

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
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $erreurs[] = $e->getMessage();
    }

    return $erreurs;
}