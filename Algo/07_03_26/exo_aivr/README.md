**Sélection de médicaments pour couvrir les symptômes (exo_aivr)**

Objectif

- Trouver une combinaison de médicaments qui couvre les besoins d'un patient pour chaque symptôme, en minimisant le coût total.

Modèle mathématique

- `effects` : matrice n x t où `effects[i][j]` représente l'effet (quantitatif) du médicament i sur le symptôme j.
- `prices` : liste des prix p_i pour chaque médicament i.
- `need` : vecteur de longueur t indiquant le besoin ou la gravité requise pour chaque symptôme.

Fichiers

- `med_select.py` : contient deux méthodes principales :
	- `solve_exact(effects, prices, need)` : recherche exhaustive (solution optimale si n petit).
	- `solve_greedy(effects, prices, need)` : heuristique gloutonne (rapide, approximative).

Exécution

1. Ouvrez un terminal à la racine du projet.
2. Lancez la démonstration :

```bash
python3 Algo/07_03_26/exo_aivr/med_select.py
```

La démonstration affiche un exemple simple, la solution exacte (si trouvée) et la solution gloutonne.

Remarques et limitations

- `solve_exact` a une complexité exponentielle en `n` (nombre de médicaments) ; l'utiliser seulement pour de petits ensembles (par exemple n ≤ 20).
- `solve_greedy` est une heuristique : rapide mais sans garantie d'optimalité. Elle choisit les médicaments en fonction du meilleur ratio (couverture effective / prix).
- Le modèle suppose que les effets s'additionnent linéairement et qu'il n'y a pas d'interactions négatives entre médicaments.

Extensions possibles

- Parser des entrées depuis un fichier JSON/CSV.
- Retourner toutes les combinaisons de coût minimal (si plusieurs solutions optimales existent).
- Ajouter des contraintes supplémentaires (compatibilités, doses maximales, effets secondaires).

Si vous voulez que je mette en place l'une de ces extensions, dites laquelle et je l'ajoute.
