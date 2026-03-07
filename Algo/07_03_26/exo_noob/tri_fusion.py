"""Tri fusion récursif et parcours infixe d'un arbre binaire.
Usage: python3 tri_fusion.py
"""

def merge(left, right):
    i = j = 0
    merged = []
    while i < len(left) and j < len(right):
        if left[i] <= right[j]:
            merged.append(left[i])
            i += 1
        else:
            merged.append(right[j])
            j += 1
    if i < len(left):
        merged.extend(left[i:])
    if j < len(right):
        merged.extend(right[j:])
    return merged


def merge_sort(arr):
    if len(arr) <= 1:
        return arr
    mid = len(arr) // 2
    left = merge_sort(arr[:mid])
    right = merge_sort(arr[mid:])
    return merge(left, right)


class Node:
    def __init__(self, val, left=None, right=None):
        self.val = val
        self.left = left
        self.right = right


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
    # Exemple tri fusion
    sample = [38, 27, 43, 3, 9, 82, 10]
    print('Avant tri :', sample)
    sorted_sample = merge_sort(sample)
    print('Après tri :', sorted_sample)

    # Construire l'arbre fourni et afficher l'ordre trié (parcours infixe)
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

    print('Parcours infixe (tri) de l\'arbre :', inorder(root))
