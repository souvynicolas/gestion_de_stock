<?php
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../includes/functions_layout.php';
    require_once __DIR__ . '/../../includes/functions_historisations.php';
    require_once __DIR__ . '/../../includes/functions_pieces.php';
    require_once __DIR__ . '/../../includes/functions_recherche.php';
    require_once __DIR__ . '/../../includes/layout.php';
    
    
    
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $hst_pce = $_GET['id'] ?? null;
            $hst_article = $_GET['article'] ?? null;
            $hst_etape_precedente = $_GET['etape_precedente'] ?? null;
            $hst_etape_en_cours = $_GET['etape_en_cours'] ?? null;
            $hst_anomalie = $_GET['anomalie'] ?? null;
            $hst_date_debut = $_GET['date_debut'] ?? null;
            $hst_date_fin = $_GET['date_fin'] ?? null;
        }

    $recuperer= rechercheHistorisation( $pdo ,$hst_pce, $hst_article, $hst_etape_precedente, $hst_etape_en_cours,$hst_anomalie, $hst_date_debut, $hst_date_fin );
    $erreurs = $recuperer['errors'];
    $resultat = $recuperer['resultat'];

        /*$historisations=selectAllHistorisation($pdo);*/


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../assets/style.css">
    <title>Document</title>
</head>
<body class="body_historisation">
    <?php afficherHeader(); ?>
    <h1>Historisation</h1>
    <?php afficherErreurs($erreurs); ?>
    <form method="GET" class="form_recherche">
        <div class="champ">
            <label for="id">Numéro de pièce :</label>
            <input type="text" name="id"
            value="<?= htmlspecialchars((string)$hst_pce) ?>">
        </div>
        <div class="champ">
            <?php creerListePce($pdo, "articles", "art_libelle", "art_id", "article", "art_temoin_de_suppression"); ?>
        </div>
        

        <div class="champ">
            <?php creerListePce($pdo,"etapes","etp_libelle", "etp_id", "etape_precedente","etp_temoin_suppression") ;?>
        </div>
         <div class="champ">
            <label for="date_debut">Date de création de :</label>
            <input type="date" name="date_debut"
            value="<?= htmlspecialchars((string)$hst_date_debut) ?>">
        </div>

       

        <div class="champ">
            <?php creerListePce($pdo,"types_anomalie","tano_libelle", "tano_id", "anomalie", "tano_temoin_suppression") ;?>
        </div>
        <div class="champ">
                <?php creerListePce($pdo,"etapes","etp_libelle", "etp_id", "etape_en_cours","etp_temoin_suppression") ;?>
        </div>

        
    <div class="champ">
        <label for="date_fin">à :</label>
        <input type="date" name="date_fin"
        value="<?= htmlspecialchars((string)$hst_date_fin) ?>">
    </div>
    <div class="actions-recherche">
        <button type="submit">Rechercher</button>
        <a href="/gestion_de_stock/pages/historisations/historisations.php">Réinitialiser</a>
    </div>
    </form>
    <?php afficherTableauHistorisations($resultat, ['hst_', 'libelle']); ?>
</body>