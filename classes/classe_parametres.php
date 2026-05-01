<?php

class parametres
{
    public function __construct(private PDO $pdo) {}

    public function creerListeParams(
        string $table,
        string $value_one,
        string $value_two,
        ?string $where = null
    ): array {
        $sql = "SELECT $value_one, $value_two FROM $table";

        if (!empty($where)) {
            $sql .= " WHERE $table.$where = 1";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAllCouleurs(): array
    {
        $sql = "SELECT cou_id AS id, cou_libelle AS couleurs
                FROM couleurs
                WHERE cou_temoin_de_suppression = 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAllDimensions(): array
    {
        $sql = "SELECT dim_id AS id, dim_libelle AS dimensions
                FROM dimensions
                WHERE dim_temoin_de_suppression = 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAllEtapes(): array
    {
        $sql = "SELECT etp_id AS id, etp_libelle AS etapes
                FROM etapes
                WHERE etp_temoin_suppression = 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAllMatieres(): array
    {
        $sql = "SELECT mat_id AS id, mat_libelle AS matieres
                FROM matieres
                WHERE mat_temoin_de_suppression = 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectAllAnomalies(): array
    {
        $sql = "SELECT tano_id AS id, tano_libelle AS anomalies
                FROM types_anomalie
                WHERE tano_temoin_suppression = 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creerParametres(string $insert, array $params): void
    {
        $sql = "INSERT INTO $insert VALUES (?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    public function modifierParametres(
        string $table,
        string $set,
        string $id,
        string $mj_date,
        array $params
    ): void {
        $sql = "UPDATE $table
                SET $set = ?,
                    $mj_date = NOW()
                WHERE $id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    public function supprimerParametres(
        string $table,
        string $temoin_sup,
        string $id,
        string $mj_date,
        int|string $params
    ): bool {
        $sql = "UPDATE $table
                SET $temoin_sup = 0,
                    $mj_date = NOW()
                WHERE $id = ?";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$params]);
    }
}