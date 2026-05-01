<?php
require_once __DIR__ . '/../../includes/layout.php';
require_once __DIR__ . '/couleurs/parametres_couleurs.php';
require_once __DIR__ . '/anomalies/parametres_anomalies.php';
require_once __DIR__ . '/dimensions/parametres_dimensions.php';
require_once __DIR__ . '/etapes/parametres_etapes.php';
require_once __DIR__ . '/matieres/parametres_matieres.php';
require_once __DIR__ . '/../../classes/classe_parametres.php';
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres</title>
    <link rel="stylesheet" type="text/css" href="../../assets/style.css">
</head>
<body class="body_parametres">
    <?php afficherHeader(); ?>
    

    <main class="parametres-main">
        <div class="parametres-grid">
            <div class="bloc-parametre">
                <?php include __DIR__ . '/couleurs/couleurs.php'; ?>
            </div>

            <div class="bloc-parametre">
                <?php include __DIR__ . '/matieres/matieres.php'; ?>
            </div>

            <div class="bloc-parametre">
                <?php include __DIR__ . '/dimensions/dimensions.php'; ?>
            </div>

            <div class="bloc-parametre">
                <?php include __DIR__ . '/etapes/etapes.php'; ?>
            </div>
            <div class="bloc-parametre">
                <?php include __DIR__ . '/anomalies/anomalies.php'; ?>
            </div>
    </div>
    </main>

    <script src="../../assets/javascript.js"></script>
</body>
</html>