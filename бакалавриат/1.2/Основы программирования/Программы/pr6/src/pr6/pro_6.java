package pr6;

import java.awt.Color;
import java.awt.Graphics;
import java.io.File;
import java.io.FileNotFoundException;
import java.util.Scanner;

import javax.swing.JPanel;

public class pro_6 extends JPanel {
	static int n, m; 
	static int[][] matrixA;
	static int[][] matrixB;
	public static void getInt(){
		   try {
		    @SuppressWarnings("resource")
		    Scanner sc = new Scanner(new File("C:\\te\\java\\j.txt"));
		    n = sc.nextInt();
		    System.out.println(n);
		    m = sc.nextInt();
		    System.out.println(m);
		   
		    matrixA = new int[n][m];
		    for (int i = 0; i < n; i++) {
		        for (int j = 0; j < m; j++) {
		         matrixA[i][j] = sc.nextInt();
		         System.out.print(matrixA[i][j] + " ");
		        }
		        System.out.println();
		    }
		    
		    int jmax = 0;
			int max = matrixA[0][0];
			 for (int i = 0; i < n; i++) {
			    for (int j = 0; j < m; j++) 
		    		if (max < matrixA[i][j]) {
		    			max = matrixA[i][j];
		    			jmax = j;
		    		}
			    }
			 System.out.print("максимальный элемент массива: " + max);
			 System.out.println();
			 
			
			 matrixB = new int[n][m + 1];
			 
			 
			 int k = -1;
			 for (int i = 0; i < n; i++) {
			        for (int j = 0; j < m; j++) {
			            if (j < jmax)
		                 {
		                   matrixB[i][j] = matrixA[i][j];
		                 }
		                 else if (j > jmax)
		                 {
		                   matrixB[i][j - 1] = matrixA[i][j];
		                 }
		                 else
		                 {
		                   continue;
		                 }
			        }
			    }
			 
			 
			 
			 
			 for (int i = 0; i < n; i++) {
			        for (int j = 0; j < m - 1; j++) {
			        	System.out.print(matrixB[i][j] + " ");
			        }
			        System.out.println();
			    }
		   } catch (FileNotFoundException e) {
		    // TODO Auto-generated catch block
		    e.printStackTrace();
		   }
		  }
	
	public void paint(Graphics g) {
		 	getInt();
			super.paint(g);
			 for (int i = 0; i < n; i++) {
			        for (int j = 0; j < m - 1; j++) {
			        	g.setColor(new Color(matrixB[i][j]*30,matrixB[i][j]*30,matrixB[i][j]*30));
			        	g.fillRect(j*20, i*20, 20, 20);
			        }
			 }
	}
}
