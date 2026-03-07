#!/usr/bin/env python3
"""Insertion ABR itérative + inorder itératif.
Usage: python3 insert_abr_iter.py
"""

class Node:
    def __init__(self, val, left=None, right=None):
        self.val = val
        self.left = left
        self.right = right


def insert_iter(root, val):
    if root is None:
        return Node(val)
    cur = root
    while True:
        if val < cur.val:
            if cur.left is None:
                cur.left = Node(val)
                break
            cur = cur.left
        elif val > cur.val:
            if cur.right is None:
                cur.right = Node(val)
                break
            cur = cur.right
        else:
            break
    return root


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
    root = Node(8,
                Node(3, Node(1), Node(6, Node(4), Node(7))),
                Node(10, Node(9), Node(14, Node(13), Node(15))))

    print('Inordre avant :', inorder_iter(root))
    for v in [11, 2]:
        print('Insertion de', v)
        insert_iter(root, v)
        print('Inordre après :', inorder_iter(root))
