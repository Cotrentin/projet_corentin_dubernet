<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('C:/MAMP/htdocs/php/projet_corentin_dubernet/TP_9/model/pdo.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID d'étudiant invalide.");
}

$etudiant_id = (int)$_GET['id'];

$query = "SELECT id, prenom, nom, classe_id FROM etudiants WHERE id = :id";
$stmt = $dbPDO->prepare($query);
$stmt->execute(['id' => $etudiant_id]);
$etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$etudiant) {
    die("Étudiant non trouvé.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nouveau_prenom = htmlspecialchars($_POST['prenom']);
    $nouveau_nom = htmlspecialchars($_POST['nom']);
    $nouvelle_classe_id = (int)$_POST['classe'];
    
    $query_update = "UPDATE etudiants SET prenom = :prenom, nom = :nom, classe_id = :classe_id WHERE id = :id";
    $stmt_update = $dbPDO->prepare($query_update);
    
    // try et catch me permettent de gérer les erreurs de base de données parce que sinon le programme s'arrête c plus simple
    try {
        $success = $stmt_update->execute([
            'prenom' => $nouveau_prenom,
            'nom' => $nouveau_nom,
            'classe_id' => $nouvelle_classe_id,
            'id' => $etudiant_id
        ]);
        
        if ($success) {
            $message = "Informations de l'étudiant mises à jour avec succès";
           
            $etudiant['prenom'] = $nouveau_prenom;
            $etudiant['nom'] = $nouveau_nom;
            $etudiant['classe_id'] = $nouvelle_classe_id;
        } else {
            $message = "Erreur lors de la mise à jour.";
        }
    } catch (PDOException $e) {
        $message = "Erreur de base de données : " . $e->getMessage();
    }
}

$query_classes = "SELECT id, libelle FROM classes ORDER BY libelle";
$stmt_classes = $dbPDO->query($query_classes);
$classes = $stmt_classes->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un étudiant</title>
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
            background-color:rgb(203, 230, 209);
            color:rgb(21, 88, 37);
            border: 1px 
        }
        .error {
            background-color:rgb(246, 214, 217);
            color:rgb(112, 28, 36);
            border: 1px 
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 15px;
            background-color:rgb(79, 181, 82);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        .btn:hover {
            background-color:rgb(69, 161, 74);
        }
    </style>
</head>
<body>
    <h1>Modifier un étudiant</h1>
    
    <?php if(isset($message)): ?>
    <div class="message <?php echo isset($success) && $success ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" 
                   value="<?php echo htmlspecialchars($etudiant['prenom']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" 
                   value="<?php echo htmlspecialchars($etudiant['nom']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="classe">Classe:</label>
            <select id="classe" name="classe" required>
                <option value="">-- Sélectionnez une classe --</option>
                <?php foreach($classes as $classe): ?>
                    <option value="<?php echo $classe['id']; ?>" 
                        <?php echo ($etudiant['classe_id'] == $classe['id'] ? 'selected' : ''); ?>>
                        <?php echo htmlspecialchars($classe['libelle']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit" class="btn">Mettre à jour</button>
    </form>
    
    <a href="../index.php" class="btn">Retour à l'accueil</a>
</body>
</html>