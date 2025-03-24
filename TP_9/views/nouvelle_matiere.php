<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once('C:/MAMP/htdocs/php/projet_corentin_dubernet/TP_9/model/pdo.php');

?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['libelle'])) {
   
    $libelle = htmlspecialchars($_POST['libelle']);
    
    
    $query = "INSERT INTO matiere (lib) VALUES (:libelle)";
    $stmt = $dbPDO->prepare($query);
    
    $success = $stmt->execute([
        'libelle' => $libelle
    ]);
    
    if($success) {
        $message = "La matière \"$libelle\" a été ajoutée avec succès!";
    } else {
        $message = "Erreur lors de l'ajout de la matière.";
    }
} else {
    $message = "Veuillez remplir tous les champs du formulaire.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'une nouvelle matière</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success {
            background-color:rgb(206, 231, 212);
            color:rgb(22, 88, 37);
            border: 1px 
        }
        .error {
            background-color:rgb(250, 215, 218);
            color:rgb(115, 28, 36);
            border: 1px 
        }
    </style>
</head>
<body>
    
    <h1>Ajout d'une nouvelle matière</h1>
    
    <div class="message <?php echo isset($success) && $success ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </div>
    
</body>
</html>