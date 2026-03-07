"""Génération de tous les mots de longueur n à partir d'un dictionnaire de symboles.

Fonctions:
- generate_words(dictionary, n): renvoie une liste de mots (itératif, utilise itertools.product)
- generate_words_recursive(dictionary, n): générateur récursif

Usage:
>>> generate_words(['a','b'], 2)
['aa','ab','ba','bb']

Exécution en ligne de commande:
python3 Algo/exo_moyenne/dico.py  # affiche un exemple
"""

from itertools import product
import sys


def generate_words(dictionary, n):
    """Retourne la liste de tous les mots de longueur n sur l'alphabet `dictionary`.

    dictionary: iterable de symboles (strings d'une seule lettre de préférence)
    n: longueur des mots (int >= 0)
    """
    if n < 0:
        return []
    if n == 0:
        return ['']
    return [''.join(p) for p in product(dictionary, repeat=n)]


def generate_words_recursive(dictionary, n):
    """Générateur récursif qui produit les mots de longueur n."""
    if n == 0:
        yield ''
        return
    for prefix in generate_words_recursive(dictionary, n - 1):
        for ch in dictionary:
            yield prefix + ch


def _demo():
    D = ['a', 'b','c']
    n = 2
    print('Dictionnaire =', D)
    print('n =', n)
    print('Résultat (itératif) =', generate_words(D, n))
    print('Résultat (récursif) =', list(generate_words_recursive(D, n)))


if __name__ == '__main__':
    # Si fourni en arguments: premier arg = symbole(s) sans espaces séparés par des virgules, second = n
    # Exemple: python3 dico.py a,b 3
    if len(sys.argv) >= 3:
        dict_arg = sys.argv[1]
        try:
            n = int(sys.argv[2])
        except ValueError:
            print('n doit être un entier')
            sys.exit(1)
        # support simple: "a,b,c" → ['a','b','c']
        dictionary = [s for s in dict_arg.split(',') if s != '']
        print(generate_words(dictionary, n))
    else:
        _demo()
