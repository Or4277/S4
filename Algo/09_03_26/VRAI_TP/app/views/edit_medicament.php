<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le medicament</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        h1 { text-align: center; color: #333; }
        .card { background: white; padding: 20px; border: 1px solid #ccc; }
        h2 { color: #333; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; }
        .btn { padding: 10px 20px; border: none; cursor: pointer; margin: 5px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #4a90d9; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .alert { padding: 10px; margin-bottom: 15px; }
        .alert-error { background: #f8d7da; color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modif le medicament</h1>
        
        <div class="card">
            <h2>Edition</h2>
            
            <form action="/medicament/update/<?php echo $medicament['id']; ?>" method="POST">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?php echo $medicament['nom'] ?? ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Prix (Ar)</label>
                    <input type="number" name="prix" class="form-control" step="0.01" min="0" value="<?php echo $medicament['prix'] ?? 0; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Effet</label>
                    <input type="number" name="effet" class="form-control" min="1" max="10" value="<?php echo $medicament['effet'] ?? 5; ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="/" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</body>
</html>
