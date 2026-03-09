<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Resultat de recherche</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        h1 { text-align: center; color: #333; }
        .card { background: white; padding: 20px; border: 1px solid #ccc; }
        h2 { color: #333; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .result { padding: 15px; background: #f9f9f9; margin: 10px 0; border: 1px solid #ddd; }
        .btn { padding: 10px 20px; border: none; cursor: pointer; margin: 5px; text-decoration: none; display: inline-block; }
        .btn-secondary { background: #6c757d; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Resultat de recherche</h1>
        
        <div class="card">
            <h2>Recherche: "<?php echo $terme ?? ''; ?>"</h2>
                <div class="result">
                    <p><strong>Nom:</strong> <?php echo $resultat['nom']; ?></p>
                </div>
            
            <a href="/" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</body>
</html>
