<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/layout.php';
require_once __DIR__ . '/../../includes/functions_layout.php';
require_once __DIR__ . '/../../includes/functions_stocks.php';
require_once __DIR__ . '/../../includes/functions_parametres.php';

$stocks=compterStock($pdo);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../assets/style.css">
    <title>Stock</title>
</head>
<body>
    <?php afficherHeader(); ?>
    <h1>stock</h1>
    <?php afficherTableauParametres($stocks,['art', Ecart]); ?>
     <script src="../../assets/javascript.js"></script>
</body>
</html>
    