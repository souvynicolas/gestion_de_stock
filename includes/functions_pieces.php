<?php
function afficherTableauPiece($lists,$label ) {
    
    if (empty($lists)){
        return;
    }
    $colonnes_cachees = ['art_id', 'tano_id','etapes_en_cours','etapes_precedente','anomalie'];

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

function selectAllPieces(pdo $pdo){
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
    WHERE p.pce_temoin_de_suppression = 1";
    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    


function creerListePce(PDO $pdo, string $table, string $value_one, string $value_two, ?string $info= null, ?string $where=null) {
        $info= $info ?? $table;
    $sql_liste="SELECT $value_one, $value_two from $table";
    if(!empty($where)){
        $sql_liste .=" WHERE $table.$where=1 ";}
    $stmts=$pdo->prepare($sql_liste);
    $stmts->execute();
    $listes=$stmts->fetchALL(PDO::FETCH_ASSOC);
    echo '<label for="' . htmlspecialchars($info) . '">' .  htmlspecialchars(formatLabel(ucfirst($info),"id")) . '</label>';
    echo '<select name="' . htmlspecialchars($info) . '" id="' . htmlspecialchars($info) . '" data-' . htmlspecialchars($info) . ' data-fill="' . htmlspecialchars($info) . '">';
        echo'<option value=""></option>';
    foreach( $listes as $list){
        echo '<option value= "' . htmlspecialchars((string)$list[$value_two]) . '">' . htmlspecialchars((string)$list[$value_one]) . 
        '</option>';
        }
    echo'</select>';    
}

function creerPiece(Pdo $pdo, array $params) {
        $sql=" INSERT INTO pieces_de_linge(pce_numero_article, pce_etape_en_cours,pce_type_anomalie) 
            VALUES(?, ?, ?)";
        $stmt=$pdo->prepare($sql);
        $stmt->execute($params);
    }


function modifierPiece($pdo, array $params){
    $sql="UPDATE pieces_de_linge
    SET pce_etape_en_cours= ?,pce_etape_precedente= ?, pce_type_anomalie= ?,  pce_texte_anomalie= ?, 
    pce_statut = ?,
    pce_date_mise_a_jour= NOW()
    WHERE pce_id=?";
                $stmt=$pdo->prepare($sql);
                $stmt->execute($params);
}


function supprimerPiece(PDO $pdo, int|string $params){
    $sql = "UPDATE pieces_de_linge
            SET pce_temoin_de_suppression = 0,
                pce_date_suppression= NOW()
            WHERE pce_id = ?";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$params]);
}


function changerEtapePiece(PDO $pdo, int $pce_id, ?int $nouvelle_etape, ?string $utilisateur = null): bool
{
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

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':etape' => $nouvelle_etape,
        ':user' => $utilisateur,
        ':id' => $pce_id
    ]);
}

function recupLotActifPiece(PDO $pdo, int $pce_id): ?int
{
    $sql = "SELECT lp.ltp_lot_id
            FROM lot_pieces lp
            INNER JOIN lots l ON l.lot_id = lp.ltp_lot_id
            WHERE lp.ltp_pce_id = ?
            AND lp.ltp_statut = 'dans_lot'
            AND l.lot_statut = 'actif'
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pce_id]);

    $lot_id = $stmt->fetchColumn();

    return $lot_id !== false ? (int)$lot_id : null;
}

function recupInfoPce(PDO $pdo, int $pce_id): array|false
{
    $sql = "SELECT * FROM pieces_de_linge WHERE pce_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pce_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function selectPiecesParIds(PDO $pdo, array $ids): array
{
    if (!$ids) {
    return [];
}

$ids = array_map('intval', $ids);
$place_holders = rtrim(str_repeat('?,', count($ids)), ',');


    $sql = "SELECT  
                p.pce_id AS id, a.art_libelle AS article, e1.etp_libelle AS etape_en_cours,
                e2.etp_libelle AS etape_precedente,d1.dim_libelle AS largeur,d2.dim_libelle AS longueur,
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

    $stmt = $pdo->prepare($sql);
    $stmt->execute($ids);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function recupLibelleTypeAnomalie(PDO $pdo, int $tano_id): ?string
{
    $sql = "SELECT tano_libelle
            FROM types_anomalie
            WHERE tano_id = ?
            AND tano_temoin_suppression = 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$tano_id]);

    $libelle = $stmt->fetchColumn();
    return $libelle !== false ? (trim($libelle)) : null;
}

function validationAnomaliesModalDefaut(PDO $pdo, array $types_anomalie): array
{
    $manquant = false;
    $illisible = false;

    foreach ($types_anomalie as $piece_id => $type_id) {
        if ($type_id === '' || $type_id === null) {
            continue;
        }

        $libelle = recupLibelleTypeAnomalie($pdo, (int)$type_id);

        if ($libelle === null) {
            continue;
        }

        if ($libelle === 'manquant') {
            $manquant = true;
        }

        if ($libelle === 'illisible') {
            $illisible = true;
        }
    }

    $erreurs = [];

    if ($manquant && $illisible) {
        $erreurs[] = "Impossible de déclarer une pièce manquante et une pièce illisible dans défaut.";
    }

    return $erreurs;
}

function changerEtapePieceAvecDefaut(
    PDO $pdo,
    int $pce_id,
    int $nouvelle_etape,
    ?int $type_anomalie,
    ?string $texte_anomalie,
    ?string $utilisateur = null
): bool {
    $sql = "UPDATE pieces_de_linge
            SET
                pce_etape_precedente = pce_etape_en_cours,pce_etape_en_cours = ?,pce_type_anomalie = ?,pce_texte_anomalie = ?,
                pce_date_etape = NOW(),pce_date_mise_a_jour = NOW(),pce_utilisateur = ?
            WHERE pce_id = ?
            AND pce_temoin_de_suppression = 1";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        $nouvelle_etape,
        $type_anomalie,
        $texte_anomalie,
        $utilisateur,
        $pce_id
    ]);
}
function afficherSelectTypeAnomalieParPiece(PDO $pdo, string $name, ?int $selected = null): void
{
    $sql = "SELECT tano_id, tano_libelle
            FROM types_anomalie
            WHERE tano_temoin_suppression = 1
            ORDER BY tano_libelle ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $anomalies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<select name="' . htmlspecialchars($name) . '">';
    echo '<option value=""></option>';

    foreach ($anomalies as $anomalie) {
        $isSelected = ($selected !== null && (int)$selected === (int)$anomalie['tano_id']) ? ' selected' : '';
        echo '<option value="' . (int)$anomalie['tano_id'] . '"' . $isSelected . '>';
        echo htmlspecialchars($anomalie['tano_libelle']);
        echo '</option>';
    }

    echo '</select>';
}



