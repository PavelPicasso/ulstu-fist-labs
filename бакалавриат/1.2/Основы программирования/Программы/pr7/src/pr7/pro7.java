package pr7;

import java.awt.EventQueue;

import javax.swing.JFrame;
import javax.swing.JPanel;
import java.awt.event.KeyAdapter;
import java.awt.event.KeyEvent;
import javax.swing.border.BevelBorder;

public class pro7 {

	private JFrame frame;
	pro_7 panel;
	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					pro7 window = new pro7();
					window.frame.setVisible(true);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		});
	}

	/**
	 * Create the application.
	 */
	public pro7() {
		initialize();
	}

	/**
	 * Initialize the contents of the frame.
	 */
	private void initialize() {
		frame = new JFrame();
		
		frame.addKeyListener(new KeyAdapter() {
			@Override
			public void keyPressed(KeyEvent arg0) {
				
				switch (arg0.getKeyCode())
				{
				case KeyEvent.VK_LEFT:
					pro_7.moveToLeft();
					frame.repaint();
					break;
				case KeyEvent.VK_RIGHT:
					pro_7.moveToRight();
					frame.repaint();
					break;
				case KeyEvent.VK_DOWN:
					pro_7.moveToDown();
					frame.repaint();
					break;
				case KeyEvent.VK_UP:
					pro_7.moveToUp();
					frame.repaint();
					break;
				default:
					
				}

				
			}
		});
		
		frame.setBounds(100, 100, 695, 368);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frame.getContentPane().setLayout(null);
		
		pro_7 panel = new pro_7();
		panel.getInt();
		panel.setBorder(new BevelBorder(BevelBorder.LOWERED, null, null, null, null));
		panel.setBounds(10, 11, 659, 308);
		frame.getContentPane().add(panel);
		panel.setLayout(null);
	}



}
