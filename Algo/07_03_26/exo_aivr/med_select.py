"""Sélection de médicaments pour couvrir les symptômes d'un patient.

Description :
- On suppose n médicaments M = {m1..mn} avec des prix p_i.
- t symptômes S = {s1..st}.
- Chaque médicament mi a un effet numérique sur chaque symptôme : effects[i][j].
- Un patient a des besoins/gravités pour chaque symptôme : need[j].

Problème : trouver un sous-ensemble de médicaments dont les effets combinés couvrent
les besoins (pour tout j, somme_i effects[i][j] >= need[j]) au coût total minimal.

Deux approches fournies :
- `solve_exact` : recherche exhaustive (optimale, utilisable si n petit, ex. n <= 20).
- `solve_greedy` : heuristique gloutonne basée sur l'efficacité par unité de prix (rapide, approximative).

Usage : exécuter ce fichier pour voir un exemple de démonstration.
"""

from itertools import combinations
import math
import sys


def covers(effects_subset, need):
    """Retourne True si la combinaison d'effets couvre les besoins `need`."""
    t = len(need)
    sums = [0] * t
    for eff in effects_subset:
        for j in range(t):
            sums[j] += eff[j]
    return all(sums[j] >= need[j] for j in range(t))


def solve_exact(effects, prices, need):
    """Recherche exhaustive : retourne (meilleur_coût, indices_médicaments) ou (None, None).

    Parcourt toutes les combinaisons pour trouver la solution de coût minimal.
    """
    n = len(effects)
    best_cost = None
    best_set = None
    # Parcours par taille croissante : permet de trouver plus vite des combinaisons petites
    for r in range(1, n + 1):
        for comb in combinations(range(n), r):
            effs = [effects[i] for i in comb]
            if covers(effs, need):
                cost = sum(prices[i] for i in comb)
                if best_cost is None or cost < best_cost:
                    best_cost = cost
                    best_set = list(comb)
    return best_cost, best_set


def score_coverage(effect, remaining_need):
    """Score de couverture : somme des réductions effectives par rapport au besoin restant."""
    return sum(min(e, r) for e, r in zip(effect, remaining_need))


def solve_greedy(effects, prices, need):
    """Heuristique gloutonne : sélectionne le médicament avec le meilleur ratio
    (couverture effective / prix) jusqu'à couvrir tous les besoins ou échouer.
    """
    n = len(effects)
    remaining = need[:]
    chosen = []
    available = set(range(n))
    while any(r > 0 for r in remaining):
        best = None
        best_ratio = 0
        for i in list(available):
            cov = score_coverage(effects[i], remaining)
            if cov <= 0:
                continue
            ratio = cov / prices[i]
            if ratio > best_ratio:
                best_ratio = ratio
                best = i
        if best is None:
            # impossible de couvrir les besoins restants
            return None, None
        chosen.append(best)
        available.remove(best)
        # mettre à jour remaining
        for j in range(len(remaining)):
            remaining[j] = max(0, remaining[j] - effects[best][j])
    total_cost = sum(prices[i] for i in chosen)
    return total_cost, chosen


def demo():
    # Exemple simple
    # 3 médicaments, 3 symptômes
    effects = [
        [1, 0, 2],  # m0
        [0, 2, 1],  # m1
        [1, 1, 0],  # m2
    ]
    prices = [5, 7, 3]
    need = [1, 2, 1]
    print('Effets :', effects)
    print('Prix :', prices)
    print('Besoins :', need)

    exact_cost, exact_set = solve_exact(effects, prices, need)
    print('\nSolution exacte : coût =', exact_cost, ', médicaments =', exact_set)

    greedy_cost, greedy_set = solve_greedy(effects, prices, need)
    print('Solution gloutonne : coût =', greedy_cost, ', médicaments =', greedy_set)


if __name__ == '__main__':
    # si des arguments sont fournis, on pourrait parser un format simple ; pour l'instant démo
    demo()
