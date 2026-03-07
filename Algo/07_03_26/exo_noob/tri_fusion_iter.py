"""Tri fusion itératif (bottom-up) et parcours infixe itératif.
Usage: python3 tri_fusion_iter.py
"""

def merge(left, right):
    i = j = 0
    merged = []
    while i < len(left) and j < len(right):
        if left[i] <= right[j]:
            merged.append(left[i]); i += 1
        else:
            merged.append(right[j]); j += 1
    if i < len(left): merged.extend(left[i:])
    if j < len(right): merged.extend(right[j:])
    return merged


def merge_sort_iter(arr):
    n = len(arr)
    if n <= 1: return arr[:]
    width = 1
    res = arr[:]
    while width < n:
        i = 0
        while i < n:
            left = res[i:i+width]
            right = res[i+width:i+2*width]
            res[i:i+2*width] = merge(left, right)
            i += 2*width
        width *= 2
    return res


class Node:
    def __init__(self, val, left=None, right=None):
        self.val = val
        self.left = left
        self.right = right


def inorder_iter(root):
    stack = []
    node = root
    out = []
    while stack or node:
        while node:
            stack.append(node)
            node = node.left
        node = stack.pop()
        out.append(node.val)
        node = node.right
    return out


if __name__ == '__main__':
    sample = [38, 27, 43, 3, 9, 82, 10]
    print('Avant tri :', sample)
    print('Après tri (itératif) :', merge_sort_iter(sample))

    root = Node(8,
                Node(3, Node(1), Node(6, Node(4), Node(7))),
                Node(10, Node(9), Node(14, Node(13), Node(15))))
    print('Parcours infixe itératif de l\'arbre :', inorder_iter(root))
