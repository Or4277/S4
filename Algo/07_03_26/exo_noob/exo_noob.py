class Node:
    def __init__(self, val, left=None, right=None):
        self.val = val
        self.left = left
        self.right = right


def find_max(root):
    if root is None:
        return float('-inf')
    return max(root.val, find_max(root.left), find_max(root.right))


def find_min(root):
    if root is None:
        return float('inf')
    return min(root.val, find_min(root.left), find_min(root.right))


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

    print('Maximum =', int(find_max(root)))
    print('Minimum =', int(find_min(root)))
