#include <stdio.h>




int Fibonacci(int n)
{
    if (n <= 1)
    {
        return n;
    }
    else
    {
        return Fibonacci(n - 1) + Fibonacci(n - 2);
    }
}

int main(void)
{
    int n = 2; 
    printf("Fibonacci(%d) = %d\n", n, Fibonacci(n));
    return 0;
}
