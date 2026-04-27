<?php 
function afficherCartesLots(array $lots): void
{
    if (empty($lots)) {
        echo '<div class="aucun-lot">Aucun lot ouvert.</div>';
        return;
    }

    echo '<div class="cartes">';

    foreach ($lots as $lot) {
        echo '<div class="carte-lot" data-open="lot_' . (int)$lot['lot_id'] . '">';
        echo '<h3>Lot #' . (int)$lot['lot_id'] . '</h3>';
        echo '<p><strong>Étape ID :</strong> ' . (int)$lot['lot_etape_destination'] . '</p>';
        echo '<p><strong>Étape :</strong> ' . htmlspecialchars($lot['lot_etape_libelle']) . '</p>';
        echo '<p><strong>Quantité restante :</strong> ' . (int)$lot['nb_pieces'] . '</p>';
        echo '<p><strong>Date :</strong> ' . htmlspecialchars(date('d/m/Y H:i', strtotime($lot['lot_date_creation']))) . '</p>';
        echo '</div>';
    }

    echo '</div>';
}

function afficherModalsLots(array $lots): void
{
    foreach ($lots as $lot) {
        $pieces_lot = $lot['pieces'];

        echo '<div id="lot_' . (int)$lot['lot_id'] . '" class="fenetre">';
        echo '  <div class="contenu-fenetre">';
        echo '      <div class="fenetre-header">';
        echo '          <h2>Lot #' . (int)$lot['lot_id'] . '</h2>';
        echo '          <button type="button" data-close>X</button>';
        echo '      </div>';

        echo '      <p><strong>Étape ID :</strong> ' . (int)$lot['lot_etape_destination'] . '</p>';
        echo '      <p><strong>Date :</strong> ' . htmlspecialchars(date('d/m/Y H:i', strtotime($lot['lot_date_creation']))) . '</p>';
        echo '      <p><strong>Étape :</strong> ' . htmlspecialchars($lot['lot_etape_libelle']) . '</p>';
        echo '      <p><strong>Pièces restantes dans ce lot :</strong> ' . count($pieces_lot) . '</p>';

        echo '      <form action="" method="POST">';
        echo '          <input type="hidden" name="lot_id" value="' . (int)$lot['lot_id'] . '">';

        echo '          <div class="actions-lot">';
        echo '              <button type="submit" name="action_etape" value="en stock">En stock</button>';
        echo '              <button type="submit" name="action_etape" value="en lavage">En lavage</button>';
        echo '              <button type="submit" name="action_etape" value="en cours">En cours</button>';
        echo '              <button type="submit" name="action_etape" value="en defaut">En defaut</button>';
        echo '          </div>';

        afficherTableauPiece($pieces_lot, ['pce_', 'libelle']);

        echo '      </form>';
        echo '  </div>';
        echo '</div>';
    }
}