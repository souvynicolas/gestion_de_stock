<?php

function validerTexte(string $texte, bool $interdireChiffres = false, bool $interdireLettres = false){ 
    $erreurs=[];
    $texte = trim($texte);


    if (!preg_match('/^[\p{L}0-9\s]+$/u', $texte)) {
        $erreurs[]= "Les caractères spéciaux ne sont pas autorisés.";
    }

    // lettres + espaces uniquement
    if ($interdireChiffres && !preg_match('/^[\p{L}\s]+$/u', $texte)) {
        $erreurs[]= "Les chiffres ne sont pas autorisés.";
    }

    // chiffres + espaces uniquement
    if ($interdireLettres && !preg_match('/^[0-9\s]+$/u', $texte)) {
        $erreurs[]= "Les lettres ne sont pas autorisées.";
    }

    return $erreurs;
}

function formatLabel($key, string|array $label=[] , bool $remp_underscore = true){
    $key = str_replace($label, '', $key);
    if ($remp_underscore) {
        $key= str_replace('_', ' ', $key);
    }
    return $key;
}
function afficherTableau($lists,$label ) {
    
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
                $attributes .= ' data-' . htmlspecialchars(formatLabel($keys,$label,false) ?? "") .  '="' . htmlspecialchars($data ?? "") . '"';
            }
        echo "<tr $attributes >";

        foreach ($list as $key => $value) {
            if (in_array($key, $colonnes_cachees, true)) {
                continue;
            }
            echo '<td>' . htmlspecialchars((string)$value) . '</td>';
        }
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}


function afficherErreurs(array $erreurs = []): void {
    if (empty($erreurs)) {
        return;
    }

    echo '<div class="messages">';
    foreach ($erreurs as $erreur) {
        echo '<div class="message-erreur">' . htmlspecialchars($erreur) . '</div>';
    }
    echo '</div>';
}


