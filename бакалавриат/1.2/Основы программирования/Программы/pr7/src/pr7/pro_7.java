package pr7;

import java.awt.Color;
import java.awt.Graphics;
import java.io.File;
import java.io.FileNotFoundException;
import java.util.Scanner;

import javax.swing.JPanel;

public class pro_7 extends JPanel {
	static int [][]a;
	static int N, M;
	static int lvl = 1;
	static int MAXlvl = 3;
	static Scanner sc;
	static Graphics g;
	public static void getInt() {
		try{
			//Scanner sc = new Scanner(new File("C:\\te\\java\\laber.txt"));
			if (lvl == 1) {
				sc = new Scanner(new File("C:\\te\\java\\laber.txt"));
			}
			if (lvl == 2) {
				sc = new Scanner(new File("C:\\te\\java\\laber1.txt"));
			}
			if (lvl == 3) {
				sc = new Scanner(new File("C:\\te\\java\\laber2.txt"));
			}
	        N = sc.nextInt();
	        M = sc.nextInt();
	        a = new int[N][M];
	    for (int i = 0; i < N; i++) {
	        for (int j = 0; j < M; j++) {
	         a[i][j] = sc.nextInt();
	        }
	    }
	   
		} catch (Exception ex) {}
	}
    
	
	public static void moveToLeft() {
		int i, j;
		i = 0;
		while (i < N) {
			j = 1;
			while (j < M) {
				if (a[i][j] == 7) {
					if (a[i][j - 1] == 0) {
						a[i][j - 1] = 7;
						a[i][j] = 0;
					}
					if (a[i][j - 1] == 3) {
							lvl++;
							getInt();
					}
				}
				j++;
			}
			i++;
		}
	}
	
	public static void moveToRight() {
		int i = 0;
		while (i < N) {
			int j = M - 2;	
			while (j >= 0) {
				if (a[i][j] == 7) {
					if (a[i][j + 1] == 0) {
						a[i][j + 1] = 7;
						a[i][j] = 0;
					}
				}
				j--;
			}
			i++;
		}
	}
	public static void moveToUp() {	
		int i = 1;
			while (i < N) {
				int j = 0;
				while (j < M) {
					if (a[i][j] == 7) {
						if (a[i - 1][j] == 0) {
							a[i - 1][j] = 7;
							a[i][j] = 0;
						}
						if (a[i][j - 1] == 3) {
							lvl++;
							if (lvl > MAXlvl) {
								System.exit(0);
							}
							getInt();
						}
					}
					j++;
				}
				i++;
			}
	}
	
	public static void moveToDown() {	
		int i = N - 1;
		while (i >= 0) {
			int j = 0;
			while (j < M) {
				if (a[i][j] == 7) {
					if (a[i + 1][j] == 0) {
						a[i + 1][j] = 7;
						a[i][j] = 0;
					}
				}
				j++;
			}
			i--;
		}
	}
	
	public void paint(Graphics g) {
		//super.paint(g);
		
		 for (int i = 0; i < N; i++) {
		        for (int j = 0; j < M; j++) {
		        	
		        	if (a[i][j] == 0 ) {
		        		g.setColor(new Color(200, 200, 200));
		        		g.fillRect(j*20, i*20, 20, 20);
		        	} 
		        	if (a[i][j] == 1 ) {
		        		g.setColor(new Color(0, 0, 0));
		        		g.fillRect(j*20, i*20, 20, 20);
		        	}
		        	if (a[i][j] == 3 ) {
		        		g.setColor(new Color(255, 0, 0));
		        		g.fillRect(j*20, i*20, 20, 20);
		        	}
		        	
		        	if (a[i][j] == 7 ) {
		        		g.setColor(new Color(0, 255, 0));
		        		g.fillRect(j*20, i*20, 20, 20);
		        	}
		        	
		        }
		 }
}
}
