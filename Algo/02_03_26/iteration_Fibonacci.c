#include <stdio.h>

int Fibonacci(int n){
    int a = 0;
    int b = 1; 
    int temp ;

    if (n <= 1)
    {
        return n ;
    }
    for (int i = 2 ; i < n ; i++)
    {
        temp = b;
        b = a + b ;
        a = temp ;
    }
    return b;


}


int main (){
    int n = 2;
    printf("Fibonacci(%d) = %d\n", n, Fibonacci(n));
    return 0;
}