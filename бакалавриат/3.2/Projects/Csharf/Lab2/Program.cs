using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ConsoleApplication1
{

    class Game
    {
        string Name = "professorC#";
        public string level;
        public int numberMonsters;
        public string nameSt;

        public void Previev()
        {
            Console.WriteLine("Игра: {0}\nУровень: {1}\nНазвание: {2}\nКолличество монстров: {3}", Name, level, nameSt, numberMonsters);
        }
    }

    class CSharp : Game
    {
        public CSharp(string level, int numberMonsters, string nameSt)
        {
            this.level = level;
            this.numberMonsters = numberMonsters;
            this.nameSt = nameSt;
        }


        public override bool Equals(object obj)
        {
            if (obj == null)
                return false;
            CSharp m = obj as CSharp;
            if (m as CSharp == null)
                return false;
            return m.level == this.level && m.nameSt == this.nameSt;
        }

        public override int GetHashCode()
        {
            /*
            String unitCode = null;
            if (nameSt == "Семь")
                unitCode += " 4";
            else
                unitCode += " 10";
            return Convert.ToInt32(unitCode);
            */
            return this.ToString().GetHashCode();
        }

        public override string ToString()
        {
            return base.ToString() + ": " + level + ", " + nameSt;
        }
    }

    class Program
    {
        static void Main()
        {
            CSharp obj = new CSharp("7", 12, "Перегрузка");
            CSharp obj2 = new CSharp("7", 12, "Семь");
            obj.Previev();
            Console.WriteLine(obj.GetHashCode()); //  1885199768
            Console.WriteLine(obj2.GetHashCode()); // -586581757
            Console.WriteLine("сравнение двух объектов с помощью equlas\n" + "Ответ: " + obj.Equals(obj2));
            Console.ReadLine();
        }
    }
}