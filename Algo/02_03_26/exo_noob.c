#include <stdio.h>
#include <stdlib.h>
#include <limits.h>

typedef struct Node {
	int val;
	struct Node *left;
	struct Node *right;
} Node;

Node *new_node(int v)
{
	Node *n = malloc(sizeof(Node));
	if (!n) return NULL;
	n->val = v;
	n->left = n->right = NULL;
	return n;
}

int max3(int a, int b, int c)
{
	int m = a > b ? a : b;
	return m > c ? m : c;
}

int min3(int a, int b, int c)
{
	int m = a < b ? a : b;
	return m < c ? m : c;
}

int findMax(Node *root)
{
	if (root == NULL) return INT_MIN;
	int lm = findMax(root->left);
	int rm = findMax(root->right);
	return max3(root->val, lm, rm);
}

int findMin(Node *root)
{
	if (root == NULL) return INT_MAX;
	int lm = findMin(root->left);
	int rm = findMin(root->right);
	return min3(root->val, lm, rm);
}

void free_tree(Node *root)
{
	if (!root) return;
	free_tree(root->left);
	free_tree(root->right);
	free(root);
}

int main(void)
{
		/* Exemple d'arbre :
						 8
						/ \
					 3   10
					/ \  / \
				 1  6 9  14
					 / \   / \
					 4  7  13  15
		*/
		Node *root = new_node(8);
		root->left = new_node(3);
		root->right = new_node(10);
		root->left->left = new_node(1);
		root->left->right = new_node(6);
		root->left->right->left = new_node(4);
		root->left->right->right = new_node(7);
		root->right->left = new_node(9);
		root->right->right = new_node(14);
		root->right->right->left = new_node(13);
		root->right->right->right = new_node(15);

	int mx = findMax(root);
	int mn = findMin(root);

	printf("Maximum = %d\n", mx);
	printf("Minimum = %d\n", mn);

	free_tree(root);
	return 0;
}



