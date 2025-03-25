<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('C:/MAMP/htdocs/php/projet_corentin_dubernet/TP_9/model/pdo.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID d'étudiant invalide.");
}

$etudiant_id = (int)$_GET['id'];

$query_check = "SELECT id FROM etudiants WHERE id = :id";
$stmt_check = $dbPDO->prepare($query_check);
$stmt_check->execute(['id' => $etudiant_id]);

if ($stmt_check->rowCount() == 0) {
    die("Étudiant non trouvé.");
}

$query_delete = "DELETE FROM etudiants WHERE id = :id";
$stmt_delete = $dbPDO->prepare($query_delete);

try {
    $success = $stmt_delete->execute(['id' => $etudiant_id]);
    
    if ($success) {
        $message = "Suppression de l'étudiant réussie";
        $messageType = "success";
    } else {
        $message = "Erreur lors de la suppression de l'étudiant";
        $messageType = "error";
    }
} catch (PDOException $e) {
    $message = "Erreur de base de données : " . $e->getMessage();
    $messageType = "error";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression d'un étudiant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success {
            background-color: rgb(200, 230, 206);
            color: rgb(24, 98, 41);
        }
        .error {
            background-color: rgb(236, 202, 206);
            color: rgb(114, 29, 37);
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: rgb(76, 180, 79);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: rgb(71, 162, 75);
        }
    </style>
</head>
<body>
    <h1>Suppression d'un étudiant</h1>
    
    <div class="message <?php echo $messageType; ?>">
        <?php echo $message; ?>
    </div>
    
    <a href="../index.php" class="btn">Retour à l'accueil</a>
</body>
</html>