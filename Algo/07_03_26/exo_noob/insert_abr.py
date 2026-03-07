#!/usr/bin/env python3
"""Insertion dans un ABR (arbre binaire de recherche) — démonstration.

Usage: python3 insert_abr.py
"""

class Node:
    def __init__(self, val, left=None, right=None):
        self.val = val
        self.left = left
        self.right = right


def insert(root, val):
    """Insère `val` dans l'ABR et retourne la racine."""
    if root is None:
        return Node(val)
    if val < root.val:
        root.left = insert(root.left, val)
    elif val > root.val:
        root.right = insert(root.right, val)
    return root


def inorder(root, out=None):
    if out is None:
        out = []
    if root is None:
        return out
    inorder(root.left, out)
    out.append(root.val)
    inorder(root.right, out)
    return out


if __name__ == '__main__':
    # Arbre fourni :
    #          8
    #         / \
    #        3   10
    #       / \  / \
    #      1  6 9  14
    #        / \   / \
    #       4  7  13 15
    root = Node(8,
                Node(3, Node(1), Node(6, Node(4), Node(7))),
                Node(10, Node(9), Node(14, Node(13), Node(15))))

    print('Inordre avant :', inorder(root))

    # Exemple d'insertion — changez les valeurs dans la liste ci-dessous
    for v in [11, 2]:
        print('Insertion de', v)
        insert(root, v)
        print('Inordre après :', inorder(root))
