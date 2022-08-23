package project10;

import java.io.File;
import java.util.Arrays;
import java.util.Scanner;

import javax.swing.JPanel;

public class pr10 extends JPanel {
	public static String mas;
	static int[] a;
	static int n;
	public static void getInt() {
		try{
			mas = "";
	        Scanner sc = new Scanner(new File("C:\\te\\java\\input.txt"));
	        n = sc.nextInt();
	        a = new int[n];
	    for (int i = 0; i < n; i++) {
	         a[i] = sc.nextInt();
	         mas += a[i];
	    }
	    mas += " ";
       // Arrays.sort(a);
        int t;
        for(int i = 1; i < n; i++)
            for(int j = n - 1; j >= i; j--) {
              if(a[j - 1] > a[j]) { 
                t = a[j - 1];
                a[j - 1] = a[j];
                a[j] = t;
            }
          }
        
        for (int i = 0; i < n; i++) {
        	mas += a[i];
	    }
        
		} catch (Exception ex) {}
	}
}
