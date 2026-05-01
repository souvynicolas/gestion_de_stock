<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../includes/functions_layout.php';
    require_once __DIR__ . '/../../../includes/functions_parametres.php';
    require_once __DIR__ . '/../../../classes/classe_parametres.php';

    $class_parametres = new parametres($pdo);
/*créer etapes*/
    $erreurs_matieres=[];
if($_SERVER["REQUEST_METHOD"] === "POST"  && isset($_POST["btn_vld_crt_mat"])) {     
    $mat_matieres = strtoupper(trim($_POST["matieres"] ?? ""));


    if (trim( $mat_matieres) === "") {
        $erreurs_matieres[] = "Veuillez saisir une matière.";
    }
    $erreurs_texte_matieres=validerTexte($mat_matieres,true,false);

    $erreurs_matieres = array_merge($erreurs_matieres, $erreurs_texte_matieres);
    $matieres =  $class_parametres->selectAllMatieres();

    foreach ($matieres as $matiere) {
        if ($matiere["matieres"] === $mat_matieres) {
            $erreurs_matieres[] = "Cette matière existe déjà.";
        break;
        }
    }
    if(empty($erreurs_matieres)) {
        $class_parametres->creerParametres("matieres(mat_libelle)" ,[$mat_matieres]);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

/*modifier etapes*/
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btn_vld_mdf_mat"])) { 
    $mat_id=trim($_POST["mat_id"] ?? "");
    $mat_matieres = strtoupper(trim($_POST["matieres"] ?? ""));
    

    
    if($mat_id===""){
        $erreurs_matieres[]= "Veuillez selectionnez une ligne avant.";
    }

    $mat_id = (int)$mat_id;

    if (trim($mat_matieres) === "") {
        $erreurs_matieres[] = "Veuillez saisir une matière.";
    }
    $erreurs_texte_matieres=validerTexte($mat_matieres,true,false);

    $erreurs_matieres = array_merge($erreurs_matieres, $erreurs_texte_matieres);
    $matieres =  $class_parametres->selectAllMatieres();

    foreach ($matieres as $matiere) {
        if ((int)$matiere["id"] !== $mat_id && $matiere["matieres"] === $mat_matieres) {
        $erreurs_matieres[] = "Cette matière existe déjà.";
        break;
    }
    }
    if(empty($erreurs_matieres)) {
        $class_parametres->modifierParametres("matieres","mat_libelle","mat_id","mat_date_mise_a_jour",[$mat_matieres, $mat_id]);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

/*supprimer etapes*/


if ($_SERVER["REQUEST_METHOD"] === "POST"&& isset($_POST["btn_vld_spr_mat"])) {
    $mat_id = trim($_POST["spr_mat_id"] ?? "");
    $mat_btn_sup_confirm = trim($_POST["btn_spr_confirm_mat"] ?? "");
    if ($mat_id === "") {
        $erreurs_matieres[] = "Aucun article sélectionné.";
    }

    if ($mat_btn_sup_confirm !== "oui") {
        $erreurs_matieres[] = "Suppression non confirmée.";
    }

    if (empty($erreurs_matieres)) {
        $class_parametres->supprimerParametres("matieres","mat_temoin_de_suppression","mat_id","mat_date_suppression", $mat_id);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

$matieres= $class_parametres->selectAllMatieres();


?>