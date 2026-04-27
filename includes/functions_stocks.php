<?php

function compterStock(PDO $pdo): array{
    $sql = "SELECT 
                a.art_id AS Id,
                a.art_libelle AS Libelle,

                SUM(CASE WHEN e.etp_libelle = 'en stock' THEN 1 ELSE 0 END) AS Quantite_en_stock,
                SUM(CASE WHEN e.etp_libelle = 'en cours' THEN 1 ELSE 0 END) AS Quantite_en_cours,
                SUM(CASE WHEN e.etp_libelle = 'en lavage' THEN 1 ELSE 0 END) AS Quantite_en_lavage,
                SUM(CASE WHEN e.etp_libelle = 'en defaut' THEN 1 ELSE 0 END) AS Quantite_en_defaut,

                COUNT(p.pce_id) AS Quantite_totale,
                a.art_stock_total_mini AS Stock_total_mini,
                a.art_stock_mini AS Stock_mini,

                SUM(CASE WHEN e.etp_libelle = 'en stock' THEN 1 ELSE 0 END) - a.art_stock_mini AS Ecart_stock,
                COUNT(p.pce_id) - a.art_stock_total_mini AS Ecart_stock_total

            FROM articles a
            LEFT JOIN pieces_de_linge p 
                ON p.pce_numero_article = a.art_id
                AND p.pce_statut = 'actif'
                AND p.pce_temoin_de_suppression = 1
            LEFT JOIN etapes e
                ON e.etp_id = p.pce_etape_en_cours

            WHERE a.art_temoin_de_suppression = 1

            GROUP BY
                a.art_id,
                a.art_libelle,
                a.art_stock_total_mini,
                a.art_stock_mini

            ORDER BY a.art_libelle";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function afficherTableauStock($lists,$label ) {
    
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

            if (str_contains($key, 'date')) {
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