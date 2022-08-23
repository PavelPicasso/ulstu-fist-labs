using System;
using System.Diagnostics;

namespace lab2
{
    class Program
    {
        
        static double func(double x) // функция вызова функции
        {
            return 1.5 * Math.Sin(1.5 * Math.Pow(x, 1) + 3);
            //return  (10 * x * log10(x) / log10(2.7) - (x * x) / 2); // заданная задачей функция 
        }

        static void mZolotSech(double eps, double a, double b) // сам метод
        {
            double x1, x2, y1, y2, ymin, xmin, t, er, ea; // er -> расчетная погрешность, еа->вычисляемая погрешность.
            int m, j;
            t = (Math.Sqrt(5) - 1) / 2; // то самое phi, отношение золотого сечения

            x1 = b + (b - a) / t; y1 = func(x1);
            x2 = a - (b - a) / t; y2 = func(x2);
            m = 2;
            while ((b - a) > eps)
            {
                if (y1 < y2)
                {
                    b = x2;
                    x2 = x1; y2 = y1;
                    x1 = b - (b - a) * t;
                    y1 = func(x1);
                }
                else
                {
                    a = x1;
                    x1 = x2; y1 = y2;
                    x2 = a + (b - a) * t;
                    y2 = func(x2);
                }
                m++;
            }

            if (y1 < y2) b = x2;
            else a = x1;
            xmin = (a + b) / 2.0; ymin = func(xmin); ea = (b - a) / 2.0;
        }


        static void Main(string[] args)
        {
            Console.Write("Введите a = ");
            double a = Convert.ToDouble(Console.ReadLine());
            Console.Write("Введите b = ");
            double b = Convert.ToDouble(Console.ReadLine());

            Stopwatch sw = new Stopwatch();
            for (double eps = 0.1; eps >= 0.0000000001; eps *= 0.1)
            {
                sw.Start(); // стартовая засечка
                for (int i = 0; i < 1000; i++)
                    mZolotSech(eps, a, b);
                sw.Stop(); // замер продолжительности
                Console.WriteLine($"{sw.ElapsedMilliseconds} мс {eps} eps");
            }
        }
    }
}
