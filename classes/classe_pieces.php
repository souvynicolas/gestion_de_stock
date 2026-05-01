<?php

class pieces
{
    public function __construct(private PDO $pdo) {}

    public function selectAllPieces(): array
    {
        $sql = "SELECT p.pce_id AS id, a.art_id, p.pce_statut, a.art_libelle AS article,
                       p.pce_etape_en_cours AS etapes_en_cours,
                       p.pce_etape_precedente AS etapes_precedente,
                       p.pce_type_anomalie AS anomalie,
                       d1.dim_libelle AS largeur, d2.dim_libelle AS longueur,
                       m.mat_libelle AS matiere, c.cou_libelle AS couleur,
                       e2.etp_libelle AS etape_precedente_libelle,
                       e1.etp_libelle AS etape_en_cours_libelle,
                       t.tano_libelle AS anomalie_libelle,
                       p.pce_texte_anomalie,
                       p.pce_date_creation,
                       p.pce_date_mise_a_jour
                FROM pieces_de_linge p
                LEFT JOIN articles a ON p.pce_numero_article = a.art_id
                LEFT JOIN dimensions d1 ON a.art_largeur = d1.dim_id
                LEFT JOIN dimensions d2 ON a.art_longueur = d2.dim_id
                LEFT JOIN couleurs c ON a.art_couleur = c.cou_id
                LEFT JOIN matieres m ON a.art_matiere = m.mat_id
                LEFT JOIN types_anomalie t ON p.pce_type_anomalie = t.tano_id
                LEFT JOIN etapes e1 ON p.pce_etape_en_cours = e1.etp_id
                LEFT JOIN etapes e2 ON p.pce_etape_precedente = e2.etp_id
                WHERE p.pce_temoin_de_suppression = 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creerPiece(array $params): void
    {
        $sql = "INSERT INTO pieces_de_linge
                (pce_numero_article, pce_etape_en_cours, pce_type_anomalie)
                VALUES (?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    public function modifierPiece(array $params): void
    {
        $sql = "UPDATE pieces_de_linge
                SET pce_etape_en_cours = ?,
                    pce_etape_precedente = ?,
                    pce_type_anomalie = ?,
                    pce_texte_anomalie = ?,
                    pce_statut = ?,
                    pce_date_mise_a_jour = NOW()
                WHERE pce_id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    public function supprimerPiece(int|string $params): bool
    {
        $sql = "UPDATE pieces_de_linge
                SET pce_temoin_de_suppression = 0,
                    pce_date_suppression = NOW()
                WHERE pce_id = ?";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$params]);
    }

    public function changerEtapePiece(
        int $pce_id,
        ?int $nouvelle_etape,
        ?string $utilisateur = null
    ): bool {
        $sql = "UPDATE pieces_de_linge
                SET pce_etape_precedente = pce_etape_en_cours,
                    pce_etape_en_cours = :etape,
                    pce_type_anomalie = CASE WHEN :etape IN (1,2,3) THEN NULL ELSE pce_type_anomalie END,
                    pce_texte_anomalie = CASE WHEN :etape IN (1,2,3) THEN NULL ELSE pce_texte_anomalie END,
                    pce_date_etape = NOW(),
                    pce_date_mise_a_jour = NOW(),
                    pce_utilisateur = :user
                WHERE pce_id = :id
                AND pce_temoin_de_suppression = 1";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':etape' => $nouvelle_etape,
            ':user' => $utilisateur,
            ':id' => $pce_id
        ]);
    }

    public function recupLotActifPiece(int $pce_id): ?int
    {
        $sql = "SELECT lp.ltp_lot_id
                FROM lot_pieces lp
                INNER JOIN lots l ON l.lot_id = lp.ltp_lot_id
                WHERE lp.ltp_pce_id = ?
                AND lp.ltp_statut = 'dans_lot'
                AND l.lot_statut = 'actif'
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$pce_id]);

        $lot_id = $stmt->fetchColumn();

        return $lot_id !== false ? (int)$lot_id : null;
    }

    public function recupInfoPce(int $pce_id): array|false
    {
        $sql = "SELECT * FROM pieces_de_linge WHERE pce_id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$pce_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function selectPiecesParIds(array $ids): array
    {
        if (!$ids) {
            return [];
        }

        $ids = array_map('intval', $ids);
        $place_holders = rtrim(str_repeat('?,', count($ids)), ',');

        $sql = "SELECT p.pce_id AS id,
                       a.art_libelle AS article,
                       e1.etp_libelle AS etape_en_cours,
                       e2.etp_libelle AS etape_precedente,
                       d1.dim_libelle AS largeur,
                       d2.dim_libelle AS longueur,
                       c.cou_libelle AS couleur
                FROM pieces_de_linge p
                LEFT JOIN articles a ON p.pce_numero_article = a.art_id
                LEFT JOIN dimensions d1 ON a.art_largeur = d1.dim_id
                LEFT JOIN dimensions d2 ON a.art_longueur = d2.dim_id
                LEFT JOIN couleurs c ON a.art_couleur = c.cou_id
                LEFT JOIN etapes e1 ON p.pce_etape_en_cours = e1.etp_id
                LEFT JOIN etapes e2 ON p.pce_etape_precedente = e2.etp_id
                WHERE p.pce_id IN ($place_holders)
                AND p.pce_temoin_de_suppression = 1
                ORDER BY p.pce_id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($ids);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    


    public function changerEtapePieceAvecDefaut(
        int $pce_id,
        int $nouvelle_etape,
        ?int $type_anomalie,
        ?string $texte_anomalie,
        ?string $utilisateur = null
    ): bool {
        $sql = "UPDATE pieces_de_linge
                SET pce_etape_precedente = pce_etape_en_cours,
                    pce_etape_en_cours = ?,
                    pce_type_anomalie = ?,
                    pce_texte_anomalie = ?,
                    pce_date_etape = NOW(),
                    pce_date_mise_a_jour = NOW(),
                    pce_utilisateur = ?
                WHERE pce_id = ?
                AND pce_temoin_de_suppression = 1";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $nouvelle_etape,
            $type_anomalie,
            $texte_anomalie,
            $utilisateur,
            $pce_id
        ]);
    }

    public function afficherSelectTypeAnomalieParPiece(
        string $name,
        ?int $selected = null
    ): void {
        $sql = "SELECT tano_id, tano_libelle
                FROM types_anomalie
                WHERE tano_temoin_suppression = 1
                ORDER BY tano_libelle ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $anomalies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo '<select name="' . htmlspecialchars($name) . '">';
        echo '<option value=""></option>';

        foreach ($anomalies as $anomalie) {
            $isSelected = ($selected !== null && (int)$selected === (int)$anomalie['tano_id'])
                ? ' selected'
                : '';

            echo '<option value="' . (int)$anomalie['tano_id'] . '"' . $isSelected . '>';
            echo htmlspecialchars($anomalie['tano_libelle']);
            echo '</option>';
        }

        echo '</select>';
    }
}