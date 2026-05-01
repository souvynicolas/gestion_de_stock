<?php
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../includes/functions_layout.php';
    require_once __DIR__ . '/../../includes/functions_articles.php';
    require_once __DIR__ . '/../../includes/functions_pieces.php';
    require_once __DIR__ . '/../../includes/layout.php';

    require_once __DIR__ . '/../../classes/classe_articles.php';

$class_articles = new Articles($pdo);
/*créer articles*/
    $erreurs=[];
if($_SERVER["REQUEST_METHOD"] === "POST"  && isset($_POST["btn_vld_crt_art"])) {      
    $art_nom_article=strtoupper($_POST["libelle"] ?? "");
    $art_longueur = trim($_POST["longueur_id"] ?? "") !== "" ? (int) $_POST["longueur_id"] : null;
    $art_largeur = trim($_POST["largeur_id"] ?? "") !== "" ? (int) $_POST["largeur_id"] : null;
    $art_matieres = trim($_POST["matiere_id"] ?? "") !== "" ? (int) $_POST["matiere_id"] : null;
    $art_couleurs = trim($_POST["couleur_id"] ?? "") !== "" ? (int) $_POST["couleur_id"] : null;
    $art_stock_total_mini = trim($_POST["stock_total_mini"] ?? "") !== "" ? (int) $_POST["stock_total_mini"] : null;
    $art_stock_mini = trim($_POST["stock_mini"] ?? "") !== "" ? (int) $_POST["stock_mini"] : null;
        

    
    if (!isset($_POST["libelle"]) || trim($_POST["libelle"]) === "") {
    $erreurs[] = "Le libellé est obligatoire.";
    }
    

        if(empty($erreurs)) {
            $class_articles->creerArticle([$art_nom_article,$art_longueur, $art_largeur,$art_matieres,$art_couleurs,$art_stock_total_mini,$art_stock_mini]);
            header("Location: articles.php");
            exit;
        }
}

/*modifier articles*/
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btn_vld_mdf_art"])) {  
    $art_id=trim($_POST["art_id"] ?? "");      
    $art_nom_article=$_POST["libelle"] ?? "";
    $art_longueur = trim($_POST["longueur_id"] ?? "") !== "" ? (int) $_POST["longueur_id"] : null;
    $art_largeur = trim($_POST["largeur_id"] ?? "") !== "" ? (int) $_POST["largeur_id"] : null;
    $art_matieres = trim($_POST["matiere_id"] ?? "") !== "" ? (int) $_POST["matiere_id"] : null;
    $art_couleurs = trim($_POST["couleur_id"] ?? "") !== "" ? (int) $_POST["couleur_id"] : null;
    $art_stock_total_mini = trim($_POST["stock_total_mini"] ?? "") !== "" ? (int) $_POST["stock_total_mini"] : null;
    $art_stock_mini = trim($_POST["stock_mini"] ?? "") !== "" ? (int) $_POST["stock_mini"] : null;

    
    if (!isset($_POST["libelle"]) || trim($_POST["libelle"]) === "") {
    $erreurs[] = "Le libellé est obligatoire.";
}

        if(empty($erreurs)) {
             $class_articles->modifierArticles([$art_nom_article,$art_longueur, $art_largeur,$art_matieres,$art_couleurs,$art_stock_total_mini,$art_stock_mini,$art_id]);
        header("Location: articles.php");
        exit;
        }
    }
/* supprimer articles*/


if ($_SERVER["REQUEST_METHOD"] === "POST"&& isset($_POST["btn_vld_spr_art"])) {
    $art_id = trim($_POST["spr_art_id"] ?? "");
    $btn_sup_confirm = trim($_POST["btn_spr_confirm_art"] ?? "");

    if ($art_id === "") {
        $erreurs[] = "Aucun article sélectionné.";
    }

    if ($btn_sup_confirm !== "oui") {
        $erreurs[] = "Suppression non confirmée.";
    }

    if (empty($erreurs)) {
         $class_articles->supprimerArticle($art_id);
        header("Location: articles.php");
        exit;
    }
}

$articles= $class_articles->selectAllArticles();

    

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../assets/style.css">
    <title>article</title>
</head>
<body>
    
    <?php afficherHeader(); ?>
    <h1>Article</h1>
    <?php afficherErreurs($erreurs); ?>
    <div class="bouton_article">
        <button type="button"  data-open="fenetre_creer_art">Créer article</button>
        <button type="button" id="btn_mdf_art">Modifier article</button>
        <button type="button" id="btn_spr_art">Supprimer article</button>
    </div>

    <?php afficherTableauArt($articles,['a.','art_', 'c.', 'd1', 'd2','dim','m.']); ?>

    <div id="fenetre_creer_art" class="fenetre">
        <?php include 'creer_articles.php'; ?>
    </div>

    <div id="fenetre_mdf_art" class="fenetre">
        <?php include 'modifier_articles.php'; ?>
    </div>
    <div id="fenetre_spr_art" class="fenetre">
        <?php include 'supprimer_articles.php'; ?>
    </div>
    <script src="../../assets/javascript.js"></script>
    </body>

</html>


