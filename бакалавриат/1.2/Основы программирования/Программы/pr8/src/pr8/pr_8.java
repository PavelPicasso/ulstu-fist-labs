package pr8;

import java.awt.EventQueue;

import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.JButton;
import javax.swing.JTextArea;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;
import javax.swing.border.BevelBorder;

public class pr_8 {

	private JFrame frame;
	pro8 panel;
	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					pr_8 window = new pr_8();
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
	public pr_8() {
		initialize();
	}

	/**
	 * Initialize the contents of the frame.
	 */
	private void initialize() {
		frame = new JFrame();
		frame.setBounds(100, 100, 950, 408);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frame.getContentPane().setLayout(null);
		
		final pro8 panel = new pro8();
		panel.setBorder(new BevelBorder(BevelBorder.LOWERED, null, null, null, null));
		panel.setBounds(10, 11, 914, 348);
		frame.getContentPane().add(panel);
		panel.setLayout(null);
		
		final JTextArea textArea = new JTextArea();
		textArea.setBounds(10, 34, 428, 303);
		panel.add(textArea);
		
		JButton btnNewButton = new JButton("Do it");
		btnNewButton.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
				panel.getStr();
				textArea.setText(panel.s);
			}
		});
		btnNewButton.setBounds(403, 11, 89, 15);
		panel.add(btnNewButton);
		
		JTextArea NewtextArea = new JTextArea();
		NewtextArea.setBounds(459, 34, 445, 303);
		panel.add(NewtextArea);
		
		
	}
}
