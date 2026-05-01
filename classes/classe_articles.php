<?php

class Articles
{
    public function __construct(private PDO $pdo) {}

    public function selectAllArticles(): array
    {
        $sql = "SELECT a.art_id AS id,
                a.art_libelle AS libelle,
                a.art_largeur AS largeur_id,
                a.art_longueur AS longueur_id,
                a.art_couleur AS couleur_id,
                a.art_matiere AS matiere_id,
                d1.dim_libelle AS largeur,
                d2.dim_libelle AS longueur,
                c.cou_libelle AS couleur,
                m.mat_libelle AS matiere,
                a.art_stock_total_mini AS stock_total_mini,
                a.art_stock_mini AS stock_mini,
                a.art_temoin_de_suppression,
                a.art_date_creation,
                a.art_date_mise_a_jour,
                a.art_date_suppression
        FROM articles a
        LEFT JOIN dimensions d1 ON a.art_largeur = d1.dim_id
        LEFT JOIN dimensions d2 ON a.art_longueur = d2.dim_id
        LEFT JOIN couleurs c ON a.art_couleur = c.cou_id
        LEFT JOIN matieres m ON a.art_matiere = m.mat_id
        WHERE a.art_temoin_de_suppression = 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creerArticle(array $params): void
    {
        $sql = "INSERT INTO articles(
                    art_libelle,
                    art_longueur,
                    art_largeur,
                    art_matiere,
                    art_couleur,
                    art_stock_total_mini,
                    art_stock_mini
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    public function modifierArticles(array $params): void
    {
        $sql = "UPDATE articles
                SET art_libelle = ?,
                    art_longueur = ?,
                    art_largeur = ?,
                    art_matiere = ?,
                    art_couleur = ?,
                    art_stock_total_mini = ?,
                    art_stock_mini = ?,
                    art_date_mise_a_jour = NOW()
                WHERE art_id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    public function supprimerArticle(int|string $params): bool
    {
        $sql = "UPDATE articles
                SET art_temoin_de_suppression = 0,
                    art_date_suppression = NOW()
                WHERE art_id = ?";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$params]);
    }
}