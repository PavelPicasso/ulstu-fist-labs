import java.util.Scanner;
public class new1 {

	public static void main(String[] args) {
		Scanner sc = new Scanner(System.in);
		int a = sc.nextInt();
		int n = sc.nextInt();
		int k = 1;
		int degit = a;
		do  {
			k++;
			degit *= a;
		} while (k < n);
		System.out.print(degit);
		sc.close();
	}

}
