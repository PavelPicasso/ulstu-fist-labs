using System;
using System.Diagnostics;
using System.Diagnostics.CodeAnalysis;

namespace lab1._1
{
    class Program
    {

		static double Dfdx(double x)
		{
			return Math.Cos(x) - (x * x * x);
		}

		static double D2fdx2(double x)
		{
			return -Math.Sin(x) - 3 * (x * x);
		}

		private static void Newton(double x, double eps)
		{
			double f, df;
			
			do
			{
				f = Dfdx(x);
				df = D2fdx2(x);
				x = x - f / df;
			} while (Math.Abs(f) > eps);
		}

		static void Main(string[] args)
        {
			Console.WriteLine("2. f(x) = cos(x) - (x^3) | x0 = 0.5");

			Stopwatch sw = new Stopwatch();
			for (double eps = 0.1; eps >= 0.0000000001; eps *= 0.1)
			{
				sw.Start(); // стартовая засечка
				for (int i = 0; i < 100000; i++)
					Newton(0.5, eps);
				sw.Stop(); // замер продолжительности
				Console.WriteLine($"{sw.ElapsedMilliseconds} мс {eps} eps");
			}
		}
    }
}
