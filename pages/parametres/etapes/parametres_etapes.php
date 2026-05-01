<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../includes/functions_layout.php';
    require_once __DIR__ . '/../../../includes/functions_parametres.php';
    require_once __DIR__ . '/../../../classes/classe_parametres.php';

    $class_parametres = new parametres($pdo);
/*créer etapes*/
$erreurs_etapes=[];
if($_SERVER["REQUEST_METHOD"] === "POST"  && isset($_POST["btn_vld_crt_etp"])) {     
    $etp_etapes = strtoupper(trim($_POST["etapes"] ?? ""));

   
    if (trim($etp_etapes) === "") {
        $erreurs_etapes[] = "Veuillez saisir une étape.";
    }
    $erreurs_texte_etapes=validerTexte($etp_etapes,true,false);

    $erreurs_etapes = array_merge($erreurs_etapes, $erreurs_texte_etapes);
    $etapes =  $class_parametres->selectAllEtapes();

    foreach ($etapes as $etape) {
        if ($etape["etapes"] === $etp_etapes) {
            $erreurs_etapes[] = "Cette étape existe déjà.";
        break;
        }
    }
    if(empty($erreurs_etapes)) {
        $class_parametres->creerParametres("etapes(etp_libelle)" ,[$etp_etapes]);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

/*modifier etapes*/
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btn_vld_mdf_etp"])) { 
    $etp_id=trim($_POST["etp_id"] ?? "");
    $etp_etapes = strtoupper(trim($_POST["etapes"] ?? ""));
    


    
    if($etp_id===""){
        $erreurs_etapes[]= "Veuillez selectionnez une ligne avant.";
    }

    $etp_id = (int)$etp_id;

    if (trim($etp_etapes) === "") {
        $erreurs_etapes[] = "Veuillez saisir une étape.";
    }
    $erreurs_texte_etapes=validerTexte($etp_etapes,true,false);

    $erreurs = array_merge($erreurs_etapes, $erreurs_texte_etapes);
    $etapes =  $class_parametres->selectAllEtapes();

    foreach ($etapes as $etape) {
        if ((int)$etape["id"] !== $etp_id && $etape["etapes"] === $etp_etapes) {
        $erreurs_etapes[] = "Cette étape existe déjà.";
        break;
    }
    }
    if(empty($erreurs_etapes)) {
        $class_parametres->modifierParametres("etapes","etp_libelle","etp_id","etp_date_mise_a_jour",[$etp_etapes, $etp_id]);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

/*supprimer etapes*/


if ($_SERVER["REQUEST_METHOD"] === "POST"&& isset($_POST["btn_vld_spr_etp"])) {
    $etp_id = trim($_POST["spr_etp_id"] ?? "");
    $etp_btn_sup_confirm = trim($_POST["btn_spr_confirm_etp"] ?? "");

    if ($etp_id === "") {
        $erreurs_etapes[] = "Aucun article sélectionné.";
    }

    if ($etp_btn_sup_confirm !== "oui") {
        $erreurs_etapes[] = "Suppression non confirmée.";
    }

    if (empty($erreurs_etapes)) {
        $class_parametres->supprimerParametres("etapes","etp_temoin_suppression","etp_id","etp_date_supression", $etp_id);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

$etapes= $class_parametres->selectAllEtapes();


?>