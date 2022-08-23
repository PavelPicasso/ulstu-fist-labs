using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._1
{
    public class Actor
    {
        public int id;
        public int cost;
        public string firstName;
        public string lastName;

        public Actor(int id, int cost, string firstName, string lastName)
        {
            this.id = id;
            this.cost = cost;
            this.firstName = firstName;
            this.lastName = lastName;
        }
    }

    public class Test
    {
        public int id;
        public int yearPopular;

        public Test(int id, int yearPopular)
        {
            this.id = id;
            this.yearPopular = yearPopular;
        }
    }



    class Program
    {
        static void CommonRequest(Action[] people, Test[] test)
        {

        }

        static void Main(string[] args)
        {
            Actor p1 = new Actor(1, 1030, "Keanu", "Wilson");
            Actor p2 = new Actor(2, 1030, "Jam", "Wilson");
            Actor p3 = new Actor(3, 3100, "Sandra", "Reeves");
            Actor p4 = new Actor(4, 700, "Gim", "Vollen");

            Actor[] people = new Actor[] { p1, p2, p3, p4 };

            Test t1 = new Test(1, 1987);
            Test t2 = new Test(2, 1998);
            Test t3 = new Test(3, 2007);
            Test t4 = new Test(4, 2002);

            Test[] test = new Test[] { t1, t2, t3, t4 };

            /*
             * 
            |вывод условия на цену|
            var subset = people.TakeWhile(s => s.cost < 1500);
            var subset = from s in people where (s.cost > 1000) select s;
            foreach (var s in subset)
            {
                Console.WriteLine("{0} - {1} - {2} - {3}", s.id, s.cost, s.firstName, s.lastName);
            }


            |найти минимум|
            var subset = people.Min();
            Console.WriteLine(subset);

            |преобразовать в лист или массив|
            ToArray() / ToList()
            
            |проверка всей коллекции|
            bool all = people.All(s => s.cost < 3000);
            Console.WriteLine("Правда ли, что все разценки коллекции people меньше 3000: " + all);

            |сортировка|
            IEnumerable<Actor> auto = people.OrderBy(s => s.cost);
            foreach (Actor str in auto)
                Console.WriteLine("{0} - {1} - {2} - {3}", s.id, s.cost, s.firstName, s.lastName);

            |соединяет две входные последовательности|
            IEnumerable<Actor> auto = people.Take(4).Concat(people.Skip(4));
            foreach (Actor str in auto)
                Console.WriteLine(str);
            *
            */

            //Этот код сначала упорядочивает элементы по их стоимости,Затем упорядочивает по самому элементу.
            IEnumerable<Actor> auto = people.OrderBy(s => s.cost);
            foreach (Actor str in auto)
                Console.WriteLine("{0} - {1} - {2} - {3}", str.id, str.cost, str.firstName, str.lastName);

            var item = people.Join(
            test,
            e => e.id,
            o => o.id,
            (e, o) => new
            {
                id = e.id,
                name = string.Format("{0} - {1}", e.firstName, e.lastName),
                year = o.yearPopular,
                cost = e.cost

            });
            foreach (var g in item)
                Console.WriteLine(g);
        }
    }
}
