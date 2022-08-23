//#define _CRT_SECURE_NO_WARNINGS 

#include<stdio.h>
#include<ctime>
#include<iostream>
#define n  3
#define m  3

 void main(){
     int a[n][m],b[n][m],c[n][m] = {}, d[n][m] = {};
     int i, j, k;

   for(int i = 0; i < n; i++)
        for(int j = 0; j< m; j++)
            scanf("%7d",& a[i][j]); 
   printf("\n");
   printf("Isxodna9 matrix");
   printf("\n");
     for(int i = 0; i < n; i++){
        for(int j = 0; j< m; j++)
            printf("%7d", a[i][j]); 
            printf("\n");
            printf("\n");
            printf("\n");
     }

    for(int i = 0; i < n; i++)
        for(int j = 0; j< m; j++)
            b[i][j] = a[i][j];
    printf("ymnoshenie(*) matrix a i b");
    printf("\n");
     for(int i = 0; i < n; i++)
        for(int j = 0; j< m; j++){
            for(int k = 0; k< m ; k++)
            c[i][j] += a[i][k] * b[k][j];
            d[i][j] += a[i][j] +b[i][j];
            }
            
             for(int i = 0; i < n; i++){
                for(int j = 0; j< m; j++)
                    printf("%7d",c[i][j]);
                    printf("\n");
             }

             printf("\n");printf("\n");printf("\n");
             printf("summa(+) matrix a i b");
             printf("\n");
    for(int i = 0; i < n; i++){
        for(int j = 0; j< m; j++)
            printf("%7d",d[i][j]);
            printf("\n");
            printf("\n");
            
    }
    int p;
    scanf("%d", &p);
}