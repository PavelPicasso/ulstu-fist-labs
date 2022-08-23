using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ConsoleApplication1
{

    interface IDrawable
    {
        void Draw();
    }

    interface IGeometrical : IDiagonal
    {
        void GetPerimeter();
        void GetArea();
    }

    interface IDiagonal
    {
        void GetDiagonal();
    }

    interface CGeometrical
    {
        void GetPerimeter();
        void GetArea();
        void GetRadius();
    }

    class Rectangle : IGeometrical, IDrawable
    {
        public void GetPerimeter()
        {
            Console.WriteLine("P=(a+b)*2");
        }

        public void GetArea()
        {
            Console.WriteLine("S=a*b");
        }

        public void Draw()
        {
            Console.WriteLine("Rectangle");
        }
        public void GetDiagonal()
        {
            Console.WriteLine("D=sqrt(a^2+b^2)");
        }
    }
    class Circle : CGeometrical, IDrawable
    {

        public void GetPerimeter()
        {
            Console.WriteLine("P=2*pi*r");
        }

        public void GetArea()
        {
            Console.WriteLine("S=pi*r^2");
        }

        public void Draw()
        {
            Console.WriteLine("Circle");
        }
        public void GetRadius()
        {
            Console.WriteLine("Для описанной окружности около правильного треугольнка : R=a/sqrt(3)");
            Console.WriteLine("Для вписанной окружности около правильного треугольнка : r=a/2*sqrt(3)");
        }
    }
    class Program
    {
        static void Main()
        {
            Rectangle obj1 = new Rectangle();
            Circle obj2 = new Circle();
            obj1.Draw();
            obj1.GetPerimeter();
            obj1.GetArea();
            obj1.GetDiagonal();
            Console.WriteLine();
            obj2.Draw();
            obj2.GetPerimeter();
            obj2.GetArea();
            obj2.GetRadius();

        }
    }
}