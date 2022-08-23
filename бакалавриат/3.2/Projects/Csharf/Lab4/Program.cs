using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ConsoleApplication1
{
    class Person : IComparable<Person>
    {
        public string Name;
        public int Age;

        public Person(string name, int age)
        {
            this.Name = name;
            this.Age = age;
        }

        public int CompareTo(Person p)
        {
            return this.Age.CompareTo(p.Age);
        }
    }
    interface IComparer
    {
        int Compare(object o1, object o2);
    }
    class PeopleComparer : IComparer<Person>
    {
        public int Compare(Person p1, Person p2)
        {
            if (p1.Age > p2.Age)
                return 1;
            else
                return -1;

        }
    }
    class Program
    {
        static void Main()
        {
            Person p1 = new Person("Bill", 31);
            Person p2 = new Person("Tom", 19);
            Person p3 = new Person("Alice", 21);
            Person p4 = new Person("Miu", 18);

            Person[] people = new Person[] { p1, p2, p3, p4 };
            Array.Sort(people, new PeopleComparer());

            foreach (Person p in people)
            {
                Console.WriteLine("{0} - {1}", p.Name, p.Age);
            }
        }
    }
}
