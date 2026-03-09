<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre Ordonnance</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        h1 { text-align: center; color: #333; }
        .card { background: white; padding: 20px; margin: 15px 0; border: 1px solid #ccc; }
        h2 { color: #333; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .success-box { background: #d4edda; padding: 15px; text-align: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #28a745; color: white; }
        .price { font-weight: bold; color: #28a745; }
        .symptome-tag { display: inline-block; background: #28a745; color: white; padding: 5px 10px; margin: 3px; }
        .gravite { background: #dc3545; color: white; padding: 2px 6px; margin-left: 5px; font-size: 12px; }
        .guerison-box { padding: 15px; text-align: center; margin: 15px 0; }
        .guerison-box.gueri { background: #d4edda; border: 2px solid #28a745; }
        .guerison-box.pas-gueri { background: #f8d7da; border: 2px solid #dc3545; }
        .total-section { background: #e8f5e9; padding: 20px; text-align: center; margin-top: 15px; }
        .total-amount { font-size: 28px; font-weight: bold; color: #28a745; }
        .btn { padding: 10px 20px; border: none; cursor: pointer; margin: 5px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #28a745; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .combinaison-card { background: #f9f9f9; border: 1px solid #ddd; padding: 15px; margin: 10px 0; }
        .combinaison-card.selected { border: 2px solid #28a745; background: #e8f5e9; }
        .combinaison-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .combinaison-prix { font-weight: bold; color: #28a745; }
        .combinaison-effet { color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Votre Ordonnance</h1>
        
        <div class="success-box">
            <h2>Budget Suffisant</h2>
            <p>Votre budget permet de couvrir le traitement.</p>
        </div>
        
        <div class="card">
            <h2>Détails</h2>
            
            <h3>Symptômes traites:</h3>
            <div>
                    <?php foreach ($symptomesSelectionnes as $s): ?>
                        <span class="symptome-tag">
                            <?php echo $s['nom']; ?>
                            <span class="gravite">Gravité: <?php echo $s['gravite'] ?? 0; ?></span>
                        </span>
                    <?php endforeach; ?>
            </div>
            
            <?php 
            $gueri = isset($resultatGuerison['gueri']) && $resultatGuerison['gueri'];
            $effetTotal = $resultatGuerison['effet_total'] ?? 0;
            $graviteRestante = $resultatGuerison['gravite_restante'] ?? 0;
            ?>
            <div class="guerison-box <?php echo $gueri ? 'gueri' : 'pas-gueri'; ?>">
                <p>Gravite totale: <strong><?php echo $graviteTotal ?? 0; ?></strong></p>
                <p>Effet des medicaments: <strong><?php echo $effetTotal; ?></strong></p>
                <p>Gravité restante: <strong><?php echo $graviteRestante; ?></strong></p>
                <hr>
                <?php if ($gueri): ?>
                    <p><strong>Le patient sera gueri !</strong></p>
                <?php else: ?>
                    <p><strong>Guerison incomplete (reste: <?php echo $graviteRestante; ?>)</strong></p>
                <?php endif; ?>
            </div>
            
            <h3>Medicaments prescrits:</h3>
            <?php if (!empty($medicaments)): ?>
                <table>
                    <tr><th>Medicament</th><th>Effet</th><th>Prix</th></tr>
                    <?php foreach ($medicaments as $med): ?>
                    <tr>
                        <td><strong><?php echo $med['nom']; ?></strong></td>
                        <td>-<?php echo $med['effet'] ?? 0; ?></td>
                        <td class="price"><?php echo number_format($med['prix'], 0); ?> Ar</td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
            
            <div class="total-section">
                <p>Total:</p>
                <div class="total-amount"><?php echo number_format($prixTotal ?? 0, 0); ?> Ar</div>
                <p>Budget: <?php echo number_format($budget ?? 0, 0); ?> Ar | Reste: <?php echo number_format(($budget ?? 0) - ($prixTotal ?? 0), 0); ?> Ar</p>
            </div>
        </div>
        
        <!-- Toutes les combinaisons -->
        <div class="card">
            <h2>Toutes les combinaisons possibles (<?php echo count($toutesCombinaisons ?? []); ?>)</h2>
            
            <?php if (!empty($toutesCombinaisons)): ?>
                <?php $numero = 1; ?>
                <?php foreach ($toutesCombinaisons as $comb): ?>
                    <?php $estSelectionnee = ($comb['prix'] == ($prixTotal ?? 0)); ?>
                    <div class="combinaison-card <?php echo $estSelectionnee ? 'selected' : ''; ?>">
                        <div class="combinaison-header">
                            <strong>Combinaison #<?php echo $numero++; ?> <?php echo $estSelectionnee ? '(CHOISIE)' : ''; ?></strong>
                            <span>
                                <span class="combinaison-prix"><?php echo number_format($comb['prix'], 0); ?> Ar</span>
                                <span class="combinaison-effet">| Effet: -<?php echo $comb['effet']; ?></span>
                                <?php if ($comb['prix'] <= $budget): ?>
                                    <span style="color: green;">Dans budget</span>
                                <?php else: ?>
                                    <span style="color: red;">Hors budget</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div>
                            <?php foreach ($comb['medicaments'] as $med): ?>
                                <span style="display: inline-block; background: #e0e0e0; padding: 3px 8px; margin: 2px;">
                                    <?php echo $med['nom']; ?> (<?php echo number_format($med['prix'], 0); ?> Ar, effet: -<?php echo $med['effet']; ?>)
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune combinaison trouvee.</p>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center;">
            <a href="/" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</body>
</html>
