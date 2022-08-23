package project9;

import javax.swing.JPanel;
import java.awt.Color;
import java.awt.Graphics;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileWriter;
import java.io.IOException;
import java.util.Scanner;
import java.util.StringTokenizer;

import javax.swing.JPanel;


public class pr9 extends JPanel {
	public String s;
	public int sum;
	public void getStr() {
		try{
			s = "";
			Scanner in = new Scanner(new File("C:\\te\\java\\ja.txt"));
			while(in.hasNext())
			s += in.nextLine() + "\r\n";
			in.close();
		} catch (Exception ex) {}
	}
	
	public void WriteStr() {
		try(FileWriter writer = new FileWriter("C:\\te\\java\\index.html")) {
			sum = Tokens();
	        writer.write(s);
	        writer.append(' ');
	        writer.write(""+sum);
	        writer.flush();
	    }
	    catch(IOException ex) { 
	        System.out.println(ex.getMessage());
	    } 
	}
	public int Tokens() {
		StringTokenizer st = new StringTokenizer(s, " \t\n\r,.");
		int sum = 0;
		 
		while(st.hasMoreTokens()){
			String str = st.nextToken(); 
			if (str.contains("à"))
				sum++;
			}
		return sum;
	}
	
	
}

	        
