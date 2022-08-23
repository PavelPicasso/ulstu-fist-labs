using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ConsoleApplication1
{
    class Arr
    {

        int[] _mass = new int[] { 6, 2, 1, 5, 3 };

        private void Swap(int[] arr, int left, int right)
        {
            if (left != right)
            {
                int temp = arr[left];
                arr[left] = arr[right];
                arr[right] = temp;
            }
        }

        public void ShowArr()
        {
            Console.WriteLine("Массив элементов Чисел:");
            for (int i = 0; i < _mass.Length; i++)
            {
                Console.Write(_mass[i] + " ");
            }
            Console.WriteLine("\n");
        }
        public void ShowArr(String[] str)
        {
            Console.WriteLine("Массив элементов Строк:");
            for (int i = 0; i < str.Length; i++)
            {
                Console.Write(str[i] + " ");
            }
            Console.WriteLine("\n");
        }
        public void ShowArr(double[] mas)
        {
            Console.WriteLine("Массив элементов Вещественных чисел:");
            for (int i = 0; i < mas.Length; i++)
            {
                Console.Write(mas[i] + " ");
            }
            Console.WriteLine("\n");
        }

        public void Sort()
        {
            for (int i = 1; i < _mass.Length; i++)
            {
                int j = i;
                while (j > 0 && _mass[j - 1] > _mass[j])
                {
                    Swap(_mass, j - 1, j);
                    j--;
                }
            }
        }

        public void Sort(String[] str)
        {
            for (int i = 0; i < str.Length; i++)
            {
                for (int j = 0; j < str.Length - 1; j++)
                {
                    if (str[j].CompareTo(str[j + 1]) > 0)
                    {
                        string s = str[j];
                        str[j] = str[j + 1];
                        str[j + 1] = s;
                    }
                }
            }
        }

        public void Sort(Double[] mas)
        {
            for (int i = 0; i < mas.Length; i++)
            {
                for (int j = 0; j < mas.Length - 1; j++)
                {
                    if (mas[j].CompareTo(mas[j + 1]) > 0)
                    {
                        Double s = mas[j];
                        mas[j] = mas[j + 1];
                        mas[j + 1] = s;
                    }
                }
            }
        }
    }
    class Program
    {
        static void Main(string[] args)
        {
            String[] str = new String[] { "ba", "ca", "aa" };
            Double[] mas = new double[] { 0.5, 2, -3, 10.2, 8, 9.9, -8 };

            Arr ob = new Arr();
            ob.ShowArr();
            ob.Sort();
            ob.ShowArr();

            ob.ShowArr(str);
            ob.Sort(str);
            ob.ShowArr(str);

            ob.ShowArr(mas);
            ob.Sort(mas);
            ob.ShowArr(mas);
        }
    }
}