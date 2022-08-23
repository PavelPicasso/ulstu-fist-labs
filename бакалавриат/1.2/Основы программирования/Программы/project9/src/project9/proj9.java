package project9;

import java.awt.EventQueue;

import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.border.BevelBorder;
import javax.swing.JTextArea;
import javax.swing.JButton;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;

public class proj9 {

	private JFrame frame;
	pr9 panel;
	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					proj9 window = new proj9();
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
	public proj9() {
		initialize();
	}

	/**
	 * Initialize the contents of the frame.
	 */
	private void initialize() {
		frame = new JFrame();
		frame.setBounds(100, 100, 810, 612);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frame.getContentPane().setLayout(null);
		
		final pr9 panel = new pr9();
		panel.setBorder(new BevelBorder(BevelBorder.LOWERED, null, null, null, null));
		panel.setBounds(10, 11, 774, 552);
		frame.getContentPane().add(panel);
		panel.setLayout(null);
		
		final JTextArea textArea = new JTextArea();
		textArea.setBounds(10, 52, 352, 212);
		panel.add(textArea);
		
		JButton btnDoIt = new JButton("Do it");
		btnDoIt.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				panel.getStr();
				panel.WriteStr();
				textArea.setText(panel.s);
			}
		});
		btnDoIt.setBounds(10, 11, 89, 23);
		panel.add(btnDoIt);
		
		JTextArea textArea_1 = new JTextArea();
		textArea_1.setBounds(388, 52, 333, 325);
		panel.add(textArea_1);
	}
}
