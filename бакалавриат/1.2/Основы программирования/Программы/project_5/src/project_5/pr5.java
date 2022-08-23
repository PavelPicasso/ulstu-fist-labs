package project_5;
import java.awt.Color;
import java.awt.EventQueue;
import java.awt.Graphics;
import javax.swing.JPanel;

public class pr5 extends JPanel {
	
	private static void drawStar(Graphics g, int cx, int cy, int size, int depth) {
		
		g.setColor(Color.GREEN);
		
		g.drawLine(cx - size , cy, cx - size / 4, cy - size / 4);	
		g.drawLine(cx - size / 4, cy - size / 4, cx , cy - size);
		g.drawLine(cx , cy - size, cx + size / 4, cy - size / 4);
		g.drawLine(cx + size / 4, cy - size / 4, cx + size, cy);
		g.drawLine(cx + size, cy, cx + size / 4, cy + size / 4);
		g.drawLine(cx + size / 4, cy + size / 4, cx, cy + size);
		g.drawLine(cx, cy + size, cx - size / 4, cy + size / 4);
		g.drawLine(cx - size / 4, cy + size / 4, cx - size, cy);
		/*
		if (depth > 0) 
		{
			drawStar(g, cx, cy - size , size / 2, depth - 1);
			drawStar(g, cx + size, cy, size / 2, depth - 1);
			drawStar(g, cx, cy + size, size / 2, depth - 1);
			drawStar(g, cx - size, cy, size / 2, depth - 1);
		}
		*/
		if (depth > 0) 
		{
			drawStar5(g, cx, cy - size, size / 2, depth - 1);
			drawStar5(g, cx + size, cy, size / 2, depth - 1);
			drawStar5(g, cx, cy + size, size / 2, depth - 1);
			drawStar5(g, cx - size, cy, size / 2, depth - 1);
		}
		
	}
	
	private static void drawStar5(Graphics g, int cx, int cy, int size, int depth) {
		g.setColor(Color.GREEN);
		
		g.drawLine(cx, cy - size, cx + size / 4, cy - size / 4);
		g.drawLine(cx + size / 4, cy - size / 4, cx + size, cy - size / 4);
		g.drawLine(cx + size, cy - size / 4, cx + size / 3, cy + size / 4);
		g.drawLine(cx + size / 3, cy + size / 4, cx + size / 2, cy + size);
		g.drawLine(cx + size / 2, cy + size, cx, cy + size / 4);
		g.drawLine(cx, cy + size / 4, cx - size / 2, cy + size);
		g.drawLine(cx - size / 2, cy + size, cx - size / 3, cy + size / 4);
		g.drawLine(cx - size / 3, cy + size / 4, cx - size, cy - size / 4);
		g.drawLine(cx - size, cy - size / 4, cx - size / 4, cy - size / 4);
		g.drawLine(cx - size / 4, cy - size / 4, cx, cy - size);
		/*
		if (depth > 0) 
		{
			drawStar5(g, cx, cy - size , size / 2, depth - 1);
			drawStar5(g, cx + size, cy, size / 2, depth - 1);
			drawStar5(g, cx, cy + size, size / 2, depth - 1);
			drawStar5(g, cx - size, cy, size / 2, depth - 1);
		}
		*/
		if (depth > 0) 
		{
			drawStar(g, cx, cy - size, size / 2, depth - 1);
			drawStar(g, cx + size, cy - size / 4, size / 2, depth - 1);
			drawStar(g, cx + size / 2, cy + size, size / 2, depth - 1);
			drawStar(g, cx - size / 2, cy + size, size / 2, depth - 1);
			drawStar(g, cx - size, cy - size / 4, size / 2, depth - 1);
		}
		
	}

	public void paint(Graphics g) {
		super.paint(g);
		//drawStar(g,100,100,30,2);
		drawStar5(g,400,350,100,3);
	}
}
