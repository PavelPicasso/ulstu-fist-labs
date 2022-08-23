import java.awt.EventQueue;
import java.awt.event.ActionEvent;

import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.JTextField;
import javax.swing.SwingConstants;
import javax.swing.JEditorPane;
import javax.swing.JButton;
import javax.swing.JTextPane;
import javax.swing.JTextArea;
import java.awt.event.ActionListener;
import javax.swing.border.BevelBorder;

public class arr_imax {

	private JFrame frame;
	private JTextField textField;

	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					arr_imax window = new arr_imax();
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
	public arr_imax() {
		initialize();
	}

	/**
	 * Initialize the contents of the frame.
	 */
	private void initialize() {
		frame = new JFrame();
		frame.setBounds(100, 100, 560, 430);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frame.getContentPane().setLayout(null);
		
		JPanel panel = new JPanel();
		panel.setBorder(new BevelBorder(BevelBorder.LOWERED, null, null, null, null));
		panel.setBounds(10, 22, 524, 358);
		frame.getContentPane().add(panel);
		panel.setLayout(null);
		
		JTextArea textArea = new JTextArea();
		textArea.setBounds(104, 46, 410, 301);
		panel.add(textArea);
		
		textField = new JTextField();
		textField.setToolTipText("\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u0447\u0438\u0441\u043B\u043E");
		textField.setBounds(10, 11, 86, 20);
		panel.add(textField);
		textField.setColumns(10);
		
		JButton btnNewButton = new JButton("debug");
		btnNewButton.addActionListener(new ActionListener() {
			
			public void actionPerformed(ActionEvent arg0) {

				String s = textField.getText();
				int n = Integer.parseInt(s);
				String str = "";
				str = str + " ";
				int i = 0;
				int k = n;
				int l = n;
				
				do {
				i++;
				k = n;
					do  {
						if (k > i) {
							str = str + " " + " " + " ";
						} else {
							str = str + k + " ";
						}
						if (k == 1) {
							str = str + "\n";		
						}
						k--;
					} while (k >= 1);
				l--;
				} while (l >= 1);
				
				l = n - 1;
				do  {
				k = n;
					do  {
						if (k > l) {
							str = str + " " + " " + " ";
						} else {
							str = str + k + " ";
						}
						if (k == 1) {
							str = str + "\n";	
						}
						k--;
					} while (k >= 1);
				l--;
				} while (l >= 1);
				
				textArea.setText(str);
				
			}
		}
		);
		btnNewButton.setBounds(7, 42, 89, 23);
		panel.add(btnNewButton);
		

	}

}
