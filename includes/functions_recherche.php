<?php
function recherchePiece(
    PDO $pdo,
    $id = null, $article = null, $etape_precedente = null, $etape_en_cours = null, $anomalie = null, $date_debut = null, $date_fin = null) {
    $sql = "SELECT  
                p.pce_id AS id,
                a.art_id,
                p.pce_statut,
                a.art_libelle AS article,
                p.pce_etape_en_cours AS etapes_en_cours,
                p.pce_etape_precedente AS etapes_precedente,
                p.pce_type_anomalie AS anomalie,
                d1.dim_libelle AS largeur,
                d2.dim_libelle AS longueur,
                m.mat_libelle AS matiere,
                c.cou_libelle AS couleur,
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

    $conditions = [];
    $params = [];
    $errors=[];

    if (!empty($id)) {
        $conditions[] = "p.pce_id = ?";
        $params[] = $id;
    }

    if (!empty($article)) {
        $conditions[] = "a.art_id LIKE ?";
        $params[] = "%" . $article . "%";
    }

    if (!empty($etape_precedente)) {
        $conditions[] = "p.pce_etape_precedente LIKE ?";
        $params[] = "%" . $etape_precedente . "%";
    }

    if (!empty($etape_en_cours)) {
        $conditions[] = "p.pce_etape_en_cours LIKE ?";
        $params[] = "%" . $etape_en_cours . "%";
    }

    if (!empty($anomalie)) {
        $conditions[] = "p.pce_type_anomalie LIKE ?";
        $params[] = "%" . $anomalie . "%";
    }


    if(!empty($date_debut)){
    $conditions[]= "DATE(p.pce_date_mise_a_jour) >= ?";
    $params[]=$date_debut ;
    }

    if(!empty($date_fin)){
        $conditions[]= "DATE(p.pce_date_mise_a_jour) <= ?";
        $params[]=$date_fin;
    }

    if(!empty($date_debut) && !empty($date_fin) && $date_debut > $date_fin){
        $errors[]= "La date de début est supérieure à la date de fin";
    }

    if (!empty($errors)) {
        return ['errors' => $errors, 'resultat' => []];
    }


    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return ['errors' => [], 'resultat' => $stmt->fetchAll(PDO::FETCH_ASSOC)];

}

function rechercheHistorisation(
    PDO $pdo,
    $piece = null,
    $article = null,
    $etape_precedente = null,
    $etape_en_cours = null,
    $anomalie = null,
    /*$texte_anomalie = null,*/
    $date_debut = null,
    $date_fin = null
) {
    $sql = "SELECT
                h.hst_numero_piece,
                a.art_libelle AS article,
                e1.etp_libelle AS etape_precedente,
                e2.etp_libelle AS etape_en_cours,
                t.tano_libelle AS anomalie,
                h.hst_texte_anomalie,
                h.hst_statut_piece,
                h.hst_temoin_suppression,
                h.hst_date_etape,
                h.hst_date_creation,
                h.hst_date_suppression
            FROM historisation h
            LEFT JOIN articles a ON h.hst_numero_article = a.art_id
            LEFT JOIN etapes e1 ON h.hst_etape_precedente = e1.etp_id
            LEFT JOIN etapes e2 ON h.hst_etape_en_cours = e2.etp_id
            LEFT JOIN types_anomalie t ON h.hst_type_anomalie = t.tano_id
            WHERE 1 = 1";

    $conditions = [];
    $params = [];
    $errors = [];

    if (!empty($piece)) {
        $conditions[] = "h.hst_numero_piece = ?";
        $params[] = $piece;
    }

    if (!empty($article)) {
        $conditions[] = "h.hst_numero_article = ?";
        $params[] = $article;
    }

    if (!empty($etape_precedente)) {
        $conditions[] = "h.hst_etape_precedente = ?";
        $params[] = $etape_precedente;
    }

    if (!empty($etape_en_cours)) {
        $conditions[] = "h.hst_etape_en_cours = ?";
        $params[] = $etape_en_cours;
    }

    if (!empty($anomalie)) {
        $conditions[] = "h.hst_type_anomalie = ?";
        $params[] = $anomalie;
    }

    /*if (!empty($texte_anomalie)) {
        $conditions[] = "h.hst_texte_anomalie LIKE ?";
        $params[] = "%" . $texte_anomalie . "%";
    }*/
    if (!empty($date_debut)) {
        $conditions[] = "DATE(h.hst_date_creation) >= ?";
        $params[] = $date_debut;
    }

    if (!empty($date_fin)) {
        $conditions[] = "DATE(h.hst_date_creation) <= ?";
        $params[] = $date_fin;
    }

    if (!empty($date_debut) && !empty($date_fin) && $date_debut > $date_fin) {
        $errors[] = "La date de début est supérieure à la date de fin";
    }

    if (!empty($errors)) {
        return ['errors' => $errors, 'resultat' => []];
    }

    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY h.hst_date_creation DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return ['errors' => [], 'resultat' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
}