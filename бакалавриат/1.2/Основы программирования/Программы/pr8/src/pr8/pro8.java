package pr8;

import java.awt.Color;
import java.awt.Graphics;
import java.io.File;
import java.io.FileNotFoundException;
import java.util.Scanner;
import javax.swing.JPanel;

public class pro8 extends JPanel {
	public String s;
	public void getStr() {
		try{
			s = "";
			Scanner in = new Scanner(new File("C:\\te\\java\\ja.txt"));
			while(in.hasNext())
			s += in.nextLine() + "\r\n";
			in.close();
		} catch (Exception ex) {}
	}
	

	
	
	
}
