<?php
$host = "localhost";  
$dbname = "projet_9";  
$username = "root";  
$password = "root";  

try {
    $dbPDO = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
        PDO::ATTR_EMULATE_PREPARES => false 
    ]);
    echo "Connexion réussie à la base de données"; 
} catch (PDOException $e) {
    echo "La connexion a échoué : " . $e->getMessage();
}
?>

