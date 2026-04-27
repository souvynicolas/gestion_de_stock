<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../includes/functions_layout.php';
    require_once __DIR__ . '/../../../includes/functions_parametres.php';
/*créer couleur*/
    $erreurs_couleurs=[];
if($_SERVER["REQUEST_METHOD"] === "POST"  && isset($_POST["btn_vld_crt_cou"])) {     
    $cou_couleurs = strtoupper(trim($_POST["couleurs"] ?? ""));


    if (trim($cou_couleurs) === "") {
        $erreurs_couleurs[] = "Veuillez saisir une couleur.";
    }
    $erreurs_texte_couleurs=validerTexte($cou_couleurs,true,false);

    $erreurs_couleurs = array_merge($erreurs_couleurs, $erreurs_texte_couleurs);
    $couleurs = selectAllCouleurs($pdo);

    foreach ($couleurs as $couleur) {
        if ($couleur["couleurs"] === $cou_couleurs) {
            $erreurs_couleurs[] = "Cette couleur existe déjà.";
        break;
        }
    }
    if(empty($erreurs_couleurs)) {
        creerCouleurs($pdo, [$cou_couleurs]);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

/*modifier couleur*/
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btn_vld_mdf_cou"])) { 
    $cou_id=trim($_POST["cou_id"] ?? "");
    $cou_couleurs = strtoupper(trim($_POST["couleurs"] ?? ""));
    


    
    if($cou_id===""){
        $erreurs_couleurs[]= "Veuillez selectionnez une ligne avant.";
    }

    $cou_id = (int)$cou_id;

    if (trim($cou_couleurs) === "") {
        $erreurs_couleurs[] = "Veuillez saisir une couleur.";
    }
    $erreurs_texte_couleurs=validerTexte($cou_couleurs,true,false);

    $erreurs_couleurs = array_merge($erreurs_couleurs, $erreurs_texte_couleurs);
    $couleurs = selectAllCouleurs($pdo);

    foreach ($couleurs as $couleur) {
        if ((int)$couleur["id"] !== $cou_id && $couleur["couleurs"] === $cou_couleurs) {
        $erreurs_couleurs[] = "Cette couleur existe déjà.";
        break;
    }
    }
    if(empty($erreurs_couleurs)) {
        modifierParametres($pdo,"couleurs","cou_libelle","cou_id","cou_date_mise_a_jour",[$cou_couleurs, $cou_id]);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

/*supprimer couleur*/


if ($_SERVER["REQUEST_METHOD"] === "POST"&& isset($_POST["btn_vld_spr_cou"])) {
    $cou_id = trim($_POST["spr_cou_id"] ?? "");
    $cou_btn_sup_confirm = trim($_POST["btn_spr_confirm_cou"] ?? "");

    if ($cou_id === "") {
        $erreurs_couleurs[] = "Aucun article sélectionné.";
    }

    if ($cou_btn_sup_confirm !== "oui") {
        $erreurs_couleurs[] = "Suppression non confirmée.";
    }

    if (empty($erreurs_couleurs)) {
        supprimerParametres($pdo, "couleurs","cou_temoin_de_suppression","cou_id","cou_date_suppression", $cou_id);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

$couleurs=selectAllCouleurs($pdo);

    /*afficherTableauParametres($couleurs,'');*/
?>
