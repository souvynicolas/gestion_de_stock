<?php

function afficherTableauParametres($lists,$label ) {
    
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

function creerListeParams(PDO $pdo, string $table, string $value_one, string $value_two, ?string $info= null, ?string $where=null) {
        $info= $info ?? $table;
    $sql_liste="SELECT $value_one, $value_two from $table";
    if(!empty($where)){
        $sql_liste .=" WHERE $table.$where=1 ";}
    $stmts=$pdo->prepare($sql_liste);
    $stmts->execute();
    $listes=$stmts->fetchALL(PDO::FETCH_ASSOC);
    echo '<label for="' . htmlspecialchars($info) . '">' .  htmlspecialchars(formatLabel($info,"id")) . '</label>';
    echo '<select name="' . htmlspecialchars($info) . '" id="' . htmlspecialchars($info) . '" data-' . htmlspecialchars($info) . ' data-fill="' . htmlspecialchars($info) . '">';
    if($table === "types_anomalie" || $table === "etapes" ) {
        echo'<option value=""></option>';
        }
    foreach( $listes as $list){
        echo '<option value= "' . htmlspecialchars((string)$list[$value_two]) . '">' . htmlspecialchars((string)$list[$value_one]) . 
        '</option>';
        }
    echo'</select>';    
}
/*
function selectAllCouleurs(pdo $pdo){
    $sql="SELECT cou_id AS id,cou_libelle AS couleurs
    FROM couleurs
    WHERE cou_temoin_de_suppression = 1"; 

    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function modifierParametres(PDO $pdo,string $table, string $set, string $id,string $mj_date, array $params){
    $sql="UPDATE $table
    SET $set= ?,
    $mj_date = NOW()
    WHERE $id=?";
    $stmt=$pdo->prepare($sql);
    $stmt->execute($params);
}

function supprimerParametres(PDO $pdo, string $table, string $temoin_sup,string $id,string $mj_date ,int|string $params){
    $sql = "UPDATE $table
            SET $temoin_sup = 0,
            $mj_date = NOW()
            WHERE $id = ?";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$params]);
}

function selectAllDimensions(pdo $pdo){
    $sql="SELECT dim_id AS id,dim_libelle AS dimensions
    FROM dimensions
    WHERE dim_temoin_de_suppression = 1"; 

    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function creerParametres(Pdo $pdo, string $insert, array $params) {
        $sql=" INSERT INTO $insert 
            VALUES(?)";
        $stmt=$pdo->prepare($sql);
        $stmt->execute($params);
    }

    function selectAllEtapes(pdo $pdo){
    $sql="SELECT etp_id AS id,etp_libelle AS etapes
    FROM etapes
    WHERE etp_temoin_suppression = 1"; 

    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function selectAllMatieres(pdo $pdo){
    $sql="SELECT mat_id AS id,mat_libelle AS matieres
    FROM matieres
    WHERE mat_temoin_de_suppression = 1"; 

    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function selectAllAnomalies(pdo $pdo){
    $sql="SELECT tano_id AS id,tano_libelle AS anomalies
    FROM types_anomalie
    WHERE tano_temoin_suppression = 1"; 

    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}*/