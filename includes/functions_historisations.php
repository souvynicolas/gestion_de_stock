<?php
function afficherTableauHistorisations($lists,$label ) {
    
    if (empty($lists)){
        return;
    }
    $colonnes_cachees = [''];

    echo'<div class="table_container">';
    echo '<table class="tableau" id="tableau">';
    echo '<thead class="tableau_head"><tr class="tableau_head_tr">';

    foreach (array_keys($lists[0]) as $column) {
        if (in_array($column, $colonnes_cachees, true)) {
            continue;
        }
        echo '<th class="tableau_head_th">' . htmlspecialchars (formatLabel($column,$label)) . '</th>';
    }
    echo '</tr></thead>';
    echo '<tbody class="tableau_body">';

    foreach ($lists as $list) {
        $attributes=" ";

            foreach($list as  $keys => $data){
                $attributes .= ' data-' . htmlspecialchars($keys) .  '="' . htmlspecialchars($data ?? "") . '"';
            }
        echo "<tr $attributes >";

        foreach ($list as $key => $value) {
            if (in_array($key, $colonnes_cachees, true)) {
                continue;
            }
            if (in_array($key, ['hst_date_creation', 'hst_date_mise_a_jour'], true)) {
                if (!empty($value)) {
                    $value = date('d/m/Y H:i', strtotime($value));
                }
            }
            echo '<td>' . htmlspecialchars((string)$value) . '</td>';
        }
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

function recupInfoPceHist(PDO $pdo, int $pce_id): ?array {
    $sql = "SELECT
                pce_id,
                pce_numero_article,
                pce_etape_precedente,
                pce_etape_en_cours,
                pce_statut,
                pce_temoin_de_suppression
            FROM pieces_de_linge
            WHERE pce_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pce_id]);

    $piece = $stmt->fetch(PDO::FETCH_ASSOC);

    return $piece ?: null;
}

function creerLigneHistorisation(PDO $pdo, array $params){
    $sql = "INSERT INTO historisation (hst_numero_piece,hst_numero_article,hst_etape_precedente,
        hst_etape_en_cours,hst_date_etape,hst_statut_piece,hst_temoin_suppression,
        hst_type_anomalie,hst_texte_anomalie,hst_date_creation,
        hst_date_suppression,hst_utilisateur
    ) VALUES (:piece_id,:article_id,:etape_precedente,:etape_en_cours,
        NOW(),:statut,:temoin_suppression,:type_anomalie,:texte_anomalie,NOW(),
        :date_suppression,:utilisateur
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':piece_id' => $params['piece_id'],
        ':article_id' => $params['article_id'],
        ':etape_precedente' => $params['etape_precedente'],
        ':etape_en_cours' => $params['etape_en_cours'],
        ':statut' => $params['statut'],
        ':temoin_suppression' => $params['temoin_suppression'],
        ':type_anomalie' => $params['type_anomalie'] ?? null,
        ':texte_anomalie' => $params['texte_anomalie'] ?? null,
        ':date_suppression' => $params['date_suppression'],
        ':utilisateur' => $params['utilisateur']
    ]);
}

function selectAllHistorisation(PDO $pdo){
    $sql = "SELECT h.hst_id,h.hst_numero_piece,a.art_libelle AS article,e1.etp_libelle AS etape_precedente,
                e2.etp_libelle AS etape_en_cours,t.tano_libelle AS anomalie,h.hst_texte_anomalie,h.hst_date_etape,
                h.hst_statut_piece,h.hst_temoin_suppression,h.hst_date_creation,
                h.hst_date_suppression
            FROM historisation h
            LEFT JOIN articles a ON h.hst_numero_article = a.art_id
            LEFT JOIN etapes e1 ON h.hst_etape_precedente = e1.etp_id
            LEFT JOIN etapes e2 ON h.hst_etape_en_cours = e2.etp_id
            LEFT JOIN types_anomalie t ON h.hst_type_anomalie = t.tano_id
            ORDER BY h.hst_date_creation DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
