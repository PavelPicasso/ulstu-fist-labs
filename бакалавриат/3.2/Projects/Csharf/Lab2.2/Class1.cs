using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._2
{

    public static class Lists
    {
        public static List<Actor> people = new List<Actor>();
        public static List<Test> tst = new List<Test>();

        public static string ListActor()
        {
            string st = null;
            foreach (Actor item in people)
            {
                st += item.ToString();
            }
            return st;
        }

        public static void EditActor(int id, int cost, string firstName, string lastName)
        {
            var subset = from s in people where (s.id == id) select s;
            foreach (var s in subset)
            {
                s.cost = cost;
                s.firstName = firstName;
                s.lastName = lastName;
            }
            subset = null;
        }

        public static void EditTest(int id, int yearPopular)
        {
            var subset = from s in tst where (s.id == id) select s;
            foreach (var s in subset)
            {
                s.yearPopular = yearPopular;
            }
            subset = null;
        }

        public static void AddActor(int id, int cost, string firstName, string lastName)
        {
            Actor p = new Actor(id, cost, firstName, lastName);
            people.Add(p);
            p = null;
        }

        public static string ListTest()
        {
            string st = null;
            foreach (Test item in tst)
            {
                st += item.ToString();
            }
            return st;
        }

        public static void AddTest(int id, int yearPopular)
        {
            Test p1 = new Test(id, yearPopular);
            tst.Add(p1);
        }

        public static string DeleteActor(int id)
        {
            people.RemoveAt(id);
            return "delete object Actor, where id = " + id;
        }

        public static string DeleteTest(int id)
        {
            tst.RemoveAt(id);
            return "delete object Test, where id = " + id;
        }
    }


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

        public Actor() { }

        public override string ToString()
        {
            return string.Format("id: {0} cost: {1} firstName: {2} lastName: {3}\n", id, cost, firstName, lastName);
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

        public Test() { }

        public override string ToString()
        {
            return string.Format("id: {0} yearPopular: {1}\n", id, yearPopular);
        }
    }
}
