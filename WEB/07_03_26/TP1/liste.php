<?php
session_start();
// var_dump($_SESSION['noms']);

$noms =$_SESSION['noms'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lise</title>
</head>
<body>
    <h1>Liste des noms</h1>
        <?php foreach ($noms as $nom): ?>
            <li><?php echo $nom; ?></li>
        <?php endforeach; ?>
    
</body>
</html>