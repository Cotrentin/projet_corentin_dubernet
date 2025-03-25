<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start();

require_once('config.php');
require_once(chemin . 'model/pdo.php');
require_once(chemin . 'views/nouvelle_etudiant.php');
require_once(chemin . 'views/nouvelle_matiere.php');




?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de l'école</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        ul {
            padding: 0;
        }
        li {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="section">
    <form action="Views/nouvelle_matiere.php" method="POST">
        <div>
            <label for="libelle">Libellé:</label>
            <input type="text" id="libelle" name="libelle" required>
        </div>
        <div style="margin-top: 10px;">
            <button type="submit">Valider</button>
        </div>
    </form>
</div>

    <h1>Affichage de l'ecole</h1>
    
    <div class="section">
    <h2>Liste des étudiants</h2>
    <table>
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $query_etudiants = "SELECT id, prenom, nom FROM etudiants ORDER BY nom, prenom";
            $result_etudiants = $dbPDO->query($query_etudiants);
            
            if ($result_etudiants && $result_etudiants->rowCount() > 0) {
                while ($row = $result_etudiants->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["prenom"]) . "</td>"; 
                    echo "<td>" . htmlspecialchars($row["nom"]) . "</td>";
                    echo "<td>
                        <a href='views/modif_etudiant.php?id=" . $row["id"] . "'>Modifier</a> | 
                         <a href='views/suppression_etudiant.php?id=" . $row["id"] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet étudiant ?\");'>Supprimer</a>
</td>";
                    echo "</tr>"; 
                }
            } else {
                echo "<tr><td colspan='3'>Aucun étudiant trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
    
    <div class="section">
        <h2>Liste des classes</h2>
        <ul>
            <?php
            $query_classes = "SELECT libelle FROM classes ORDER BY libelle";
            $result_classes = $dbPDO->query($query_classes);
            
            if ($result_classes && $result_classes->rowCount() > 0) {
                while ($row = $result_classes->fetch(PDO::FETCH_ASSOC)) {
                    echo "<li>" . htmlspecialchars($row["libelle"]) . "</li>";
                }
            } else {
                echo "<li>Aucune classe trouvée.</li>";
            }
            ?>
        </ul>
    </div>
    
    <div class="section">
        <h2>Liste des professeurs</h2>
        <ul>
            <?php
            $query_profs = "SELECT prenom, nom FROM professeurs ORDER BY nom, prenom";
            $result_profs = $dbPDO->query($query_profs);
            
            if ($result_profs && $result_profs->rowCount() > 0) {
                while ($row = $result_profs->fetch(PDO::FETCH_ASSOC)) {
                    echo "<li>" . htmlspecialchars($row["prenom"]) . " " . htmlspecialchars($row["nom"]) . "</li>";
                }
            } else {
                echo "<li>Aucun professeur trouvé.</li>";
            }
            ?>
        </ul>
    </div>
    
    <div class="section">
        <h2>Détails des professeurs (matière et classe)</h2> 
        <table>  
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Matière</th>
                    <th>Classe</th>
                </tr>
            </thead>
            <tbody>
                <?php
                 // cette patie me permet de simplifier l'appel des prof, matière et classe grace au alias
                $query_profs_details = "SELECT 
                    p.prenom, 
                    p.nom, 
                    m.lib AS matiere, 
                    c.libelle AS classe 
                FROM 
                    professeurs p
                JOIN 
                    matiere m ON p.id_matiere = m.id
                JOIN 
                    classes c ON p.id_classe = c.id 
                ORDER BY 
                    p.nom, p.prenom";   // grace aux join je peut savoir les info sur les classes où sont répartient les prof 

                $result_profs_details = $dbPDO->query($query_profs_details);
                
                if ($result_profs_details && $result_profs_details->rowCount() > 0) {
                    while ($row = $result_profs_details->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["nom"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["prenom"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["matiere"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["classe"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucun professeur trouvé.</td></tr>"; // colspan permet d'avoir un tableau a X colonnes ici c'est 4
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php 
$content = ob_get_clean();
echo $content; 
?>