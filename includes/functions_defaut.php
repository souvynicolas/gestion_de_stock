<?php 
function afficherTableauDefaut($lists,$label ) {
    
    if (empty($lists)){
        return;
    }
    $colonnes_cachees = [''];

    echo'<div class="table_container ">';
    echo '<table class="tableau" id="tableau">';
    echo '<thead class="tableau_head"><tr class="tableau_head_tr">';
    echo '<th class="tableau_head_th">Choix</th>';
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
        echo '<td><input type="checkbox" class="checkbox-ligne" name="pieces_check_ids[]" value="' . (int)$list['id'] . '"></td>';
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
function selectAllPiecesDefaut(pdo $pdo){
    $sql="SELECT  p.pce_id AS id,a.art_id,p.pce_statut,a.art_libelle AS article,
                p.pce_etape_en_cours AS etapes_en_cours,
                p.pce_etape_precedente AS etapes_precedente,
                p.pce_type_anomalie AS anomalie,
                d1.dim_libelle AS largeur,d2.dim_libelle AS longueur,
                m.mat_libelle AS matiere,c.cou_libelle AS couleur,
                e2.etp_libelle AS etape_precedente_libelle,
                e1.etp_libelle AS etape_en_cours_libelle,
                t.tano_libelle AS anomalie_libelle,p.pce_texte_anomalie,
                p.pce_date_creation,p.pce_date_mise_a_jour      
    FROM pieces_de_linge p
    LEFT JOIN articles a ON p.pce_numero_article=a.art_id
    LEFT JOIN dimensions d1 ON a.art_largeur = d1.dim_id
    LEFT JOIN dimensions d2 ON a.art_longueur = d2.dim_id
    LEFT JOIN couleurs c ON a.art_couleur = c.cou_id
    LEFT JOIN matieres m ON a.art_matiere = m.mat_id
    LEFT JOIN types_anomalie t ON p.pce_type_anomalie = t.tano_id 
    LEFT JOIN etapes e1 ON p.pce_etape_en_cours= e1.etp_id
    LEFT JOIN etapes e2 ON p.pce_etape_precedente= e2.etp_id
    WHERE p.pce_temoin_de_suppression = 1 AND e1.etp_libelle = 'en defaut'";
    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
