<?php
var_dump($symptomes);
var_dump($medicaments);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Système Médical</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { text-align: center; color: #333; }
        .card { background: white; padding: 20px; margin: 15px 0; border: 1px solid #ccc; }
        h2 { color: #333; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .alert { padding: 10px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: green; }
        .alert-error { background: #f8d7da; color: red; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #4a90d9; color: white; }
        .btn { padding: 8px 15px; border: none; cursor: pointer; margin: 2px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #4a90d9; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-control { width: 100%; padding: 8px; border: 1px solid #ccc; }
        .checkbox-list { border: 1px solid #ccc; padding: 10px; max-height: 250px; overflow-y: auto; }
        .checkbox-item { display: flex; align-items: center; padding: 8px; background: #f9f9f9; margin-bottom: 5px; }
        .checkbox-item input { margin-right: 10px; }
        .gravite-input { width: 50px; padding: 5px; text-align: center; margin-left: 10px; }
        .row { display: flex; gap: 10px; }
        .col-6 { flex: 1; }
        .info-box { background: #e3f2fd; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Systeme Medical - Prescription</h1>
        <div class="card">
            <h2>Gestion des Symptomes</h2>
            
            <div class="info-box">
                <strong>Total:</strong> <?php echo $count; ?> symptomes
            </div>
            
            <h3>Ajouter un symptome</h3>
            <form action="/symptome/create" method="POST">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
            </form>
            
            <h3>Liste des symptomes</h3>
                <table>
                    <tr><th>ID</th><th>Nom</th><th>Actions</th></tr>
                    <?php foreach ($symptomes as $s): ?>
                    <tr>
                        <td><?php echo $s['id']; ?></td>
                        <td><?php echo $s['nom']; ?></td>
                        <td>
                            <a href="/symptome/edit/<?php echo $s['id']; ?>" class="btn btn-warning">Modifier</a>
                            <a href="/symptome/delete/<?php echo $s['id']; ?>" class="btn btn-danger" onclick="return confirm('Supprimer?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
        </div>
        
        <div class="card">
            <h2>Gestion des Medicaments</h2>
            
            <div class="info-box">
                <strong>Total:</strong> <?php echo $countMedicaments; ?> medicaments
            </div>
            
            <h3>Ajouter un medicament</h3>
            <form action="/medicament/create" method="POST">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Prix (Ar)</label>
                            <input type="number" name="prix" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Effet</label>
                            <input type="number" name="effet" class="form-control" min="1" max="10"  required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
            </form>
            
            <h3>Liste des medicaments</h3>
                <table>
                    <tr><th>ID</th><th>Nom</th><th>Prix</th><th>Effet</th><th>Actions</th></tr>
                    <?php foreach ($medicaments as $m): ?>
                    <tr>
                        <td><?php echo $m['id']; ?></td>
                        <td><?php echo $m['nom']; ?></td>
                        <td><?php echo number_format($m['prix'], 0); ?> Ar</td>
                        <td>-<?php echo $m['effet']; ?></td>
                        <td>
                            <a href="/medicament/edit/<?php echo $m['id']; ?>" class="btn btn-warning">Modifier</a>
                            <a href="/medicament/delete/<?php echo $m['id']; ?>" class="btn btn-danger" onclick="return confirm('Supprimer?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
        </div>
        
        <div class="card">
            <h2> VOTRE DEMANDE </h2>
            
            <form action="/ordonnance" method="POST">
                <div class="form-group">
                    <label>Sélectionnez vos symptômes:</label>
                    <div class="checkbox-list">
                            <?php foreach ($symptomes as $s): ?>
                            <div class="checkbox-item">
                                <input type="checkbox" name="symptomes[]" value="<?php echo $s['id']; ?>" id="s<?php echo $s['id']; ?>">
                                <label for="s<?php echo $s['id']; ?>">
                                    <strong><?php echo $s['nom']; ?></strong>
                                </label>
                                <input type="number" name="gravites[<?php echo $s['id']; ?>]" class="gravite-input" >
                            </div>
                            <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Budget anle client (Ar):</label>
                    <input type="number" name="budget" class="form-control" style="width:200px" step="0.01"  required>
                </div>
                
                <button type="submit" class="btn btn-primary">Valider</button>
            </form>
        </div>
    </div>
</body>
</html>
