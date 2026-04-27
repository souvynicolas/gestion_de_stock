<?php 
function selectAllArticles(pdo $pdo){
    $sql="SELECT a.art_id AS id,
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

    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function afficherTableauArt($lists,$label ) {
    
    if (empty($lists)){
        return;
    }
    $colonnes_cachees = ['largeur_id', 'longueur_id', 'couleur_id', 'matiere_id', 'art_temoin_de_suppression'];

    echo'<div class="table_container">';
    echo '<table class="tableau" id="tableau" ;">';
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


function creerListeArt(PDO $pdo, string $table, string $value_one, string $value_two, ?string $info= null) {
        $info= $info ?? $table;
    $sql_liste="SELECT $value_one, $value_two from $table ";
    $stmts=$pdo->prepare($sql_liste);
    $stmts->execute();
    $listes=$stmts->fetchALL(PDO::FETCH_ASSOC);
    echo '<label for="' . htmlspecialchars($info) . '">' .  htmlspecialchars(formatLabel($info,"id")) . '</label>';
    echo '<select name="' . htmlspecialchars($info) . '" id="' . htmlspecialchars($info) . '" data-' . htmlspecialchars($info) . ' data-fill="' . htmlspecialchars($info) . '">';
    foreach( $listes as $list){
        echo '<option value= "' . htmlspecialchars((string)$list[$value_two]) . '">' . htmlspecialchars((string)$list[$value_one]) . 
        '</option>';
        }
    echo'</select>';    
}

function modifierArticles($pdo, array $params){
    $sql="UPDATE articles
    SET art_libelle= ?,art_longueur= ?, art_largeur= ?,  art_matiere= ?, 
    art_couleur = ?, art_stock_total_mini= ?, art_stock_mini= ?,
    art_date_mise_a_jour= NOW()
    WHERE art_id=?";
                $stmt=$pdo->prepare($sql);
                $stmt->execute($params);

}

function creerArticle(Pdo $pdo, array $params) {
        $sql=" INSERT INTO articles(art_libelle, art_longueur, art_largeur, art_matiere ,art_couleur, art_stock_total_mini, art_stock_mini) 
            VALUES(?, ?, ?, ?, ?, ?, ?)";
        $stmt=$pdo->prepare($sql);
        $stmt->execute($params);
    }


function supprimerArticle(PDO $pdo, int|string $params){
    $sql = "UPDATE articles
            SET art_temoin_de_suppression = 0,
                art_date_suppression= NOW()
            WHERE art_id = ?";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$params]);
}
    
