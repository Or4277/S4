"""Find max / min itératifs dans un arbre binaire."""

class Node:
    def __init__(self, val, left=None, right=None):
        self.val = val
        self.left = left
        self.right = right


def find_max_iter(root):
    if root is None:
        return float('-inf')
    stack = [root]
    maxv = float('-inf')
    while stack:
        node = stack.pop()
        if node.val > maxv: maxv = node.val
        if node.left: stack.append(node.left)
        if node.right: stack.append(node.right)
    return maxv


def find_min_iter(root):
    if root is None:
        return float('inf')
    stack = [root]
    minv = float('inf')
    while stack:
        node = stack.pop()
        if node.val < minv: minv = node.val
        if node.left: stack.append(node.left)
        if node.right: stack.append(node.right)
    return minv


if __name__ == '__main__':
    root = Node(8,
                Node(3, Node(1), Node(6, Node(4), Node(7))),
                Node(10, Node(9), Node(14, Node(13), Node(15))))

    print('Maximum (itératif) =', int(find_max_iter(root)))
    print('Minimum (itératif) =', int(find_min_iter(root)))
