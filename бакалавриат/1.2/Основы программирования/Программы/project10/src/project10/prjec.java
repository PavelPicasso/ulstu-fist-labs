package project10;

import java.awt.EventQueue;

import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.JButton;
import javax.swing.JTextArea;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;
import java.awt.Font;
import javax.swing.ImageIcon;
import java.awt.Color;
import javax.swing.UIManager;

public class prjec {

	private JFrame frame;
	pr10 panel;
	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					prjec window = new prjec();
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
	public prjec() {
		initialize();
	}

	/**
	 * Initialize the contents of the frame.
	 */
	private void initialize() {
		frame = new JFrame();
		frame.setBounds(100, 100, 180, 150);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frame.getContentPane().setLayout(null);
		
		final pr10 panel = new pr10();
		panel.setBounds(10, 11, 414, 240);
		frame.getContentPane().add(panel);
		panel.setLayout(null);
		
		final JTextArea textArea = new JTextArea();
		textArea.setForeground(Color.BLACK);
		textArea.setFont(new Font("Monospaced", Font.PLAIN, 20));
		textArea.setBackground(Color.LIGHT_GRAY);
		textArea.setBounds(0, 45, 141, 31);
		panel.add(textArea);
		
		JButton btnNewButton = new JButton("Do it");
		btnNewButton.setIcon(new ImageIcon(prjec.class.getResource("/javax/swing/plaf/basic/icons/JavaCup16.png")));
		btnNewButton.setForeground(Color.BLACK);
		btnNewButton.setBackground(UIManager.getColor("Button.background"));
		btnNewButton.setFont(new Font("SimHei", Font.PLAIN, 11));
		btnNewButton.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				panel.getInt();
				textArea.setText(panel.mas);
			}
		});
		btnNewButton.setBounds(27, 11, 89, 23);
		panel.add(btnNewButton);
		
		
	}

}
