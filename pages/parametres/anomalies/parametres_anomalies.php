<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../includes/functions_layout.php';
    require_once __DIR__ . '/../../../includes/functions_parametres.php';
    require_once __DIR__ . '/../../../classes/classe_parametres.php';

    $class_parametres = new parametres($pdo);
   $erreurs_anomalies=[];
    
    if($_SERVER["REQUEST_METHOD"] === "POST"  && isset($_POST["btn_vld_crt_tano"])) {     
    $tano_anomalies = strtoupper(trim($_POST["anomalies"] ?? ""));

    $erreurs_anomalies=[];
    if (trim( $tano_anomalies) === "") {
        $erreurs_anomalies[] = "Veuillez saisir une anomalie.";
    }
    $erreurs_texte_anomalies=validerTexte($tano_anomalies,true,false);

    $erreurs_anomalies = array_merge($erreurs_anomalies, $erreurs_texte_anomalies);
    $anomalies =  $class_parametres->selectAllAnomalies();

    foreach ($anomalies as $anomalie) {
        if ($anomalie["anomalies"] === $tano_anomalies) {
            $erreurs_anomalies[] = "Cette anomalie existe déjà.";
        break;
        }
    }
    if(empty($erreurs_anomalies)) {
        $class_parametres->creerParametres("types_anomalie(tano_libelle)" ,[$tano_anomalies]);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

/*modifier anomalies*/
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btn_vld_mdf_tano"])) { 
    $tano_id=trim($_POST["tano_id"] ?? "");
    $tano_anomalies = strtoupper(trim($_POST["anomalies"] ?? ""));
    


    
    if($tano_id===""){
        $erreurs_anomalies[]= "Veuillez selectionnez une ligne avant.";
    }

    $tano_id = (int)$tano_id;

    if (trim($tano_anomalies) === "") {
        $erreurs_anomalies[] = "Veuillez saisir une anomalie.";
    }
    $erreurs_texte_anomalies=validerTexte($tano_anomalies,true,false);

    $erreurs_anomalies = array_merge($erreurs_anomalies, $erreurs_texte_anomalies);
    $anomalies =  $class_parametres->selectAllAnomalies();

    foreach ($anomalies as $anomalie) {
        if ((int)$anomalie["id"] !== $tano_id && $anomalie["anomalies"] === $tano_anomalies) {
        $erreurs_anomalies[] = "Cette anomalie existe déjà.";
        break;
    }
    }
    if(empty($erreurs_anomalies)) {
        $class_parametres->modifierParametres("types_anomalie","tano_libelle","tano_id","tano_date_mise_a_jour",[$tano_anomalies, $tano_id]);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

/*supprimer anomalies*/


if ($_SERVER["REQUEST_METHOD"] === "POST"&& isset($_POST["btn_vld_spr_tano"])) {
    $tano_id = trim($_POST["spr_tano_id"] ?? "");
    $tano_btn_sup_confirm = trim($_POST["btn_spr_confirm_tano"] ?? "");

    if ($tano_id === "") {
        $erreurs_anomalies[] = "Aucun article sélectionné.";
    }

    if ($tano_btn_sup_confirm !== "oui") {
        $erreurs_anomalies[] = "Suppression non confirmée.";
    }

    if (empty($erreurs_anomalies)) {
        $class_parametres->supprimerParametres("types_anomalie","tano_temoin_suppression","tano_id","tano_date_supression", $tano_id);
        header("Location: /gestion_de_stock/pages/parametres/parametres.php");
        exit;
    }
}

$anomalies= $class_parametres->selectAllAnomalies();


?>