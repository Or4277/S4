**Exercices Noob — Explications et utilisation**

Ce document décrit brièvement les trois scripts présents dans ce dossier : `tri_fusion.py`, `insert_abr.py` et `exo_noob.py`. Il explique leur objectif, le principe algorithmique, la complexité et comment les exécuter.

**Arbre utilisé (contexte)** :
- Racine : 8
- Structure :

         8
        / \
       3   10
      / \  / \
     1  6 9  14
       / \   / \
      4  7  13 15

Parcours infixe (inorder) attendu de cet arbre (liste triée) :

1, 3, 4, 6, 7, 8, 9, 10, 13, 14, 15

**Fichiers et explications**

- **`tri_fusion.py` : Tri fusion récursif et démonstration**
  - But : implémenter le tri fusion (merge sort) récursif et montrer un exemple.
  - Principe : diviser pour régner — on divise la liste en deux moitiés, on trie récursivement puis on fusionne.
  - Complexité : temps O(n log n) en moyenne et pire cas, espace O(n) (pour la fusion).
  - Usage :
    - Lancer : `python3 Algo/07_03_26/tri_fusion.py`
    - Sortie : affiche la liste avant/après tri, puis affiche le parcours infixe (tri) de l'arbre fourni.

- **`insert_abr.py` : Insertion dans un ABR (arbre binaire de recherche)**
  - But : insérer un (ou plusieurs) éléments dans l'ABR donné.
  - Principe : insertion récursive — comparer la valeur avec la racine, aller à gauche si plus petit, à droite si plus grand.
  - Remarque : l'implémentation ignore les duplicates (ne les réinsère pas).
  - Complexité : O(h) où h est la hauteur de l'arbre (pire cas O(n) si l'arbre est dégénéré).
  - Usage :
    - Lancer : `python3 Algo/07_03_26/insert_abr.py`
    - Comportement : affiche l'inordre (liste triée) avant, insère les valeurs listées dans le script (ex : 11 puis 2) et affiche l'inordre après chaque insertion.
    - Personnalisation : modifier la liste `for v in [11, 2]:` dans le fichier pour tester d'autres insertions.

- **`exo_noob.py` : Maximum et Minimum dans un arbre (récursif)**
  - But : parcourir l'arbre binaire et renvoyer la valeur maximale et minimale.
  - Principe : parcours récursif (prévoir que les sous-arbres peuvent être NULL), combiner les résultats des sous-arbres avec la valeur du nœud.
  - Complexité : O(n) en temps (visite chaque nœud une fois), O(h) en espace de pile récursive.
  - Usage :
    - Lancer : `python3 Algo/07_03_26/exo_noob.py`
    - Sortie attendue (pour l'arbre fourni) :
      - `Maximum = 15`
      - `Minimum = 1`

**Notes pratiques**

- Tous les scripts sont autonomes et contiennent l'arbre « en dur » tel qu'indiqué ci-dessus.
- Pour tester d'autres arbres : modifier la construction `Node(...)` dans chaque fichier.
- Pour accepter des entrées utilisateur ou lire depuis un fichier : on peut adapter les scripts pour parser une liste d'entiers ou un format d'arbre (demandez si vous voulez cette extension).

**Commandes rapides**

```bash
python3 Algo/07_03_26/tri_fusion.py
python3 Algo/07_03_26/insert_abr.py
python3 Algo/07_03_26/exo_noob.py
```


Si vous souhaitez que je :
- ajoute la lecture depuis stdin/fichier,
- fournisse une version C pour l'un des scripts,
- ou écrive des tests unitaires automatisés,
dites lequel et je m'en occupe.

**Versions itératives**

J'ai ajouté des variantes itératives des trois algorithmes. Ci-dessous leur description et usage.

- **`tri_fusion_iter.py` : Tri fusion itératif (bottom-up)**
  - But : tri par fusion sans récursion en utilisant des sous-tableaux de largeur croissante.
  - Principe : on fusionne des segments de taille 1, puis 2, puis 4, etc., jusqu'à couvrir la liste.
  - Complexité : O(n log n) en temps, O(n) en mémoire (fusion).
  - Usage : `python3 Algo/07_03_26/tri_fusion_iter.py` (affiche avant/après et parcours infixe itératif de l'arbre).

- **`insert_abr_iter.py` : insertion itérative dans un ABR**
  - But : insérer une valeur dans l'ABR sans utiliser la récursion.
  - Principe : parcourir l'arbre avec un pointeur `cur` jusqu'à trouver la feuille où attacher le nouveau nœud.
  - Complexité : O(h) en temps où h est la hauteur de l'arbre, pas d'utilisation de la pile système.
  - Usage : `python3 Algo/07_03_26/insert_abr_iter.py` (affiche l'inordre avant/après insertions).

- **`exo_noob_iter.py` : recherche itérative du maximum et minimum**
  - But : déterminer max/min sans récursion.
  - Principe : parcours DFS itératif (pile explicite) visitant tous les nœuds, mettre à jour max/min.
  - Complexité : O(n) en temps, O(n) en pire cas pour la pile auxiliaire.
  - Usage : `python3 Algo/07_03_26/exo_noob_iter.py` (affiche Maximum et Minimum).

Les commandes d'exécution complètes sont listées plus haut et fonctionnent telles quelles.

**Fin du document `ex_noob.md`.**
