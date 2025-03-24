<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<?php

require_once('C:/MAMP/htdocs/php/projet_corentin_dubernet/TP_9/model/pdo.php');

$query_classes = "SELECT id, libelle FROM classes ORDER BY libelle";
$stmt_classes = $dbPDO->query($query_classes);
$classes = $stmt_classes->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['classe'])) {
    
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $classe_id = (int) $_POST['classe']; 
    
   
    $query = "INSERT INTO etudiants (nom, prenom, classe_id) VALUES (:nom, :prenom, :classe_id)";
    $stmt = $dbPDO->prepare($query);
    
    $success = $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'classe_id' => $classe_id
    ]);
    
    if($success) {
     
        $query_classe_nom = "SELECT libelle FROM classes WHERE id = :id";
        $stmt_classe_nom = $dbPDO->prepare($query_classe_nom);
        $stmt_classe_nom->execute(['id' => $classe_id]);
        $classe_nom = $stmt_classe_nom->fetchColumn();
        
        $message = "L'étudiant(e) \"$prenom $nom\" a été ajouté(e) avec succès dans la classe \"$classe_nom\"!";
    } else {
        $message = "Erreur lors de l'ajout de l'étudiant(e).";
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = "Veuillez remplir tous les champs du formulaire.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'un nouvel étudiant</title>
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
            background-color:rgb(205, 230, 211);
            color:rgb(24, 94, 40);
            border: 1px 
        }
        .error {
            background-color:rgb(246, 210, 213);
            color:rgb(117, 28, 37);
            border: 1px 
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        select, input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            padding: 8px 16px;
            background-color:rgb(74, 176, 78);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color:rgb(68, 159, 72);
        }
    </style>
</head>
<body>
    <h1>Ajout d'un nouvel étudiant</h1>
    
    <?php if(isset($message)): ?>
    <div class="message <?php echo isset($success) && $success ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div class="form-group">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>
        <div class="form-group">
            <label for="classe">Classe:</label>
            <select id="classe" name="classe" required>
                <option value="">-- Sélectionnez une classe --</option>
                <?php foreach($classes as $classe): ?>
                    <option value="<?php echo $classe['id']; ?>"><?php echo htmlspecialchars($classe['libelle']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <button type="submit">Valider</button>
        </div>
    </form>
   
</body>
</html>