<?php
$host = '127.0.0.1'; // ou localhost avec socket
$dbname = 'csv';
$user = 'root';
$pass = ''; // mot de passe si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des clients
$requete = $pdo->query("SELECT NumClient, Nom, Prenom, Adresse FROM client");
$clients = $requete->fetchAll(PDO::FETCH_ASSOC);

// 1. Ouvrir le fichier en écriture
$fp = fopen('client.csv', 'w');

// 2. Pour chaque client, écrire une ligne CSV
foreach ($clients as $row) {
    // On construit un tableau indexé dans l'ordre souhaité
    $ligne = [
        $row['NumClient'],
        $row['Nom'],
        $row['Prenom'],
        $row['Adresse']
    ];
    
    // 3. Écrire la ligne au format CSV avec le séparateur virgule
    fputcsv($fp, $ligne, ',');
}

// 4. Fermer le fichier
fclose($fp);

echo "Export terminé : fichier client.csv généré.";
?>