<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Budget Insuffisant</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        h1 { text-align: center; color: #333; }
        .card { background: white; padding: 20px; margin: 15px 0; border: 1px solid #ccc; }
        h2 { color: #333; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .error-box { background: #f8d7da; padding: 20px; text-align: center; margin-bottom: 15px; border: 2px solid #dc3545; }
        .error-box h2 { color: #dc3545; border: none; }
        .budget-compare { display: flex; justify-content: center; gap: 40px; margin: 15px 0; }
        .budget-item { text-align: center; }
        .budget-value { font-size: 24px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #dc3545; color: white; }
        .price { font-weight: bold; color: #dc3545; }
        .symptome-tag { display: inline-block; background: #dc3545; color: white; padding: 5px 10px; margin: 3px; }
        .gravite { background: rgba(0,0,0,0.2); padding: 2px 6px; margin-left: 5px; font-size: 12px; }
        .guerison-box { padding: 15px; text-align: center; margin: 15px 0; }
        .guerison-box.gueri { background: #d4edda; border: 2px solid #28a745; }
        .guerison-box.pas-gueri { background: #f8d7da; border: 2px solid #dc3545; }
        .total-section { background: #ffebee; padding: 20px; text-align: center; margin-top: 15px; border: 2px solid #dc3545; }
        .total-amount { font-size: 28px; font-weight: bold; color: #dc3545; }
        .manque { background: #dc3545; color: white; padding: 8px 15px; display: inline-block; margin-top: 10px; }
        .btn { padding: 10px 20px; border: none; cursor: pointer; margin: 5px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #dc3545; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .combinaison-card { background: #f9f9f9; border: 1px solid #ddd; padding: 15px; margin: 10px 0; }
        .combinaison-card.cheapest { border: 2px solid #ffc107; background: #fff8e1; }
        .combinaison-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .combinaison-prix { font-weight: bold; color: #dc3545; }
        .combinaison-effet { color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Budget Insuffisant</h1>
        
        <div class="error-box">
            <h2>Votre budget est insuffisant</h2>
            
            <div class="budget-compare">
                <div class="budget-item">
                    <div>Votre budget</div>
                    <div class="budget-value"><?php echo number_format($budget ?? 0, 0); ?> Ar</div>
                </div>
                <div class="budget-item">
                    <div>Prix minimum</div>
                    <div class="budget-value"><?php echo number_format($prixMinimum ?? 0, 0); ?> Ar</div>
                </div>
            </div>
            
            <p><strong>Il vous manque <?php echo number_format(($prixMinimum ?? 0) - ($budget ?? 0), 0); ?> Ar</strong></p>
        </div>
        
        <div class="card">
            <h2>Symptomes</h2>
            
            <div>
                    <?php foreach ($symptomesSelectionnes as $s): ?>
                        <span class="symptome-tag">
                            <?php echo $s['nom']; ?>
                            <span class="gravite">Gravite: <?php echo $s['gravite'] ?? 0; ?></span>
                        </span>
                    <?php endforeach; ?>

            </div>
            
            <?php if ($resultatGuerison): ?>
            <?php 
            $gueri = isset($resultatGuerison['gueri']) && $resultatGuerison['gueri'];
            $effetTotal = $resultatGuerison['effet_total'] ?? 0;
            $graviteRestante = $resultatGuerison['gravite_restante'] ?? 0;
            ?>
            <div class="guerison-box <?php echo $gueri ? 'gueri' : 'pas-gueri'; ?>">
                <p>Gravite totale: <strong><?php echo $graviteTotal ?? 0; ?></strong></p>
                <p>Effet de la combinaison la moins chere: <strong><?php echo $effetTotal; ?></strong></p>
                <p>Gravite restante: <strong><?php echo $graviteRestante; ?></strong></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Toutes les combinaisons -->
        <div class="card">
            <h2>Toutes les combinaisons possibles (<?php echo count($toutesCombinaisons ?? []); ?>)</h2>
            
                <?php $numero = 1; ?>
                <?php foreach ($toutesCombinaisons as $comb): ?>
                    <?php $estMoinsChere = ($combinaisonMoinsChere && $comb['prix'] == $combinaisonMoinsChere['prix']); ?>
                    <div class="combinaison-card <?php echo $estMoinsChere ? 'cheapest' : ''; ?>">
                        <div class="combinaison-header">
                            <strong>Combinaison #<?php echo $numero++; ?> <?php echo $estMoinsChere ? '(MOINS CHÈRE)' : ''; ?></strong>
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

        </div>
        
        <div style="text-align: center;">
            <a href="/" class="btn btn-primary">Modifier mon budget</a>
        </div>
    </div>
</body>
</html>
