<?php 
    $host="127.0.0.1";
    $dbname= "gestion_stock_test";
    $username="root";
    $password="root";
    $errors= [];

    try{
        $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
            PDO :: ATTR_ERRMODE => PDO :: ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

    }catch(PDOException $e) {
        $errors[]= $e-> getMessage();
    }
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>" . htmlspecialchars($error) . "</p>";
        }
    }