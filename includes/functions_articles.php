<?php 


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

