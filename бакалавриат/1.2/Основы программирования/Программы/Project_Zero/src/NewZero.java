import java.util.Scanner;
public class NewZero {

	public static void main(String[] args) {
		Scanner sc = new Scanner(System.in);
		int n = sc.nextInt();
		int i = 0;
		int k = n;
		int l = n;
		
		do {
		i++;
		k = n;
			do  {
				if (k > i) {
					System.out.print(" " + " ");
				} else {
					System.out.print(k + " ");
				}
				if (k == 1) {
					System.out.print("\n");		
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
					System.out.print(" " + " ");
				} else {
					System.out.print(k + " ");
				}
				if (k == 1) {
					System.out.print("\n");	
				}
				k--;
			} while (k >= 1);
		l--;
		} while (l >= 1);
		
		sc.close();
	}
}
