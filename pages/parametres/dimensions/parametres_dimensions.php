<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../includes/functions_layout.php';
    require_once __DIR__ . '/../../../includes/functions_parametres.php';
    require_once __DIR__ . '/../../../classes/classe_parametres.php';

    $class_parametres = new parametres($pdo);
/*créer dimensions*/

if($_SERVER["REQUEST_METHOD"] === "POST"  && isset($_POST["btn_vld_crt_dim"])) {     
    $dim_dimensions = strtoupper(trim($_POST["dimensions"] ?? ""));

    $erreurs_dimensions=[];
    if (trim($dim_dimensions) === "") {
        $erreurs_dimensions[] = "Veuillez saisir une dimension.";
    }
    $erreurs_texte_dimensions=validerTexte($dim_dimensions,false,true);

    $erreurs_dimensions = array_merge($erreurs_dimensions, $erreurs_texte_dimensions);
    $dimensions =  $class_parametres->selectAllDimensions();

    foreach ($dimensions as $dimension) {
        if ((int)$dimension["dimensions"] === (int)$dim_dimensions) {
            $erreurs_dimensions[] = "Cette dimension existe déjà.";
        break;
        }
    }
    if(empty($erreurs_dimensions)) {
        $class_parametres->creerParametres("dimensions(dim_libelle)" ,[$dim_dimensions]);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

/*modifier dimensions*/
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btn_vld_mdf_dim"])) { 
    $dim_id=trim($_POST["dim_id"] ?? "");
    $dim_dimensions = strtoupper(trim($_POST["dimensions"] ?? ""));
    

    $erreurs_dimensions=[];
    
    if($dim_id===""){
        $erreurs_dimensions[]= "Veuillez selectionnez une ligne avant.";
    }

    $dim_id = (int)$dim_id;

    if (trim( $dim_dimensions) === "") {
        $erreurs_dimensions[] = "Veuillez saisir une dimension.";
    }
    $erreurs_texte_dimensions=validerTexte( $dim_dimensions,false,true);

    $erreurs_dimensions = array_merge($erreurs_dimensions, $erreurs_texte_dimensions);
    $dimensions = $class_parametres->selectAllDimensions();

    foreach ($dimensions as $dimension) {
        if ((int)$dimension["id"] !== $dim_id && (int)$dimension["dimensions"] === (int)$dim_dimensions) {
        $erreurs_dimensions[] = "Cette dimension existe déjà.";
        break;
    }
    }
    if(empty($erreurs_dimensions)) {
        $class_parametres->modifierParametres("dimensions","dim_libelle","dim_id","dim_date_mise_a_jour",[$dim_dimensions, $dim_id]);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

/*supprimer dimensions*/


if ($_SERVER["REQUEST_METHOD"] === "POST"&& isset($_POST["btn_vld_spr_dim"])) {
    $dim_id = trim($_POST["spr_dim_id"] ?? "");
    $dim_btn_sup_confirm = trim($_POST["btn_spr_confirm_dim"] ?? "");
    $erreurs_dimensions = [];
    if ($dim_id === "") {
        $erreurs_dimensions[] = "Aucun article sélectionné.";
    }

    if ($dim_btn_sup_confirm !== "oui") {
        $erreurs_dimensions[] = "Suppression non confirmée.";
    }

    if (empty($erreurs_dimensions)) {
        $class_parametres->supprimerParametres("dimensions","dim_temoin_de_suppression","dim_id","dim_date_suppression", $dim_id);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

$dimensions= $class_parametres->selectAllDimensions();


?>
