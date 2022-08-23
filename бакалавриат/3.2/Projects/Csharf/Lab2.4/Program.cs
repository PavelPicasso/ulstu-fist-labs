using Lab2._4.Facilities;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._4
{
    class Program
    {
        static void Main(string[] args)
        {
            Info facility = new Horde();
            facility.GetUnit("Horseman");
            Console.WriteLine();
            facility.GetUnit("Archer");
            Console.WriteLine();
            facility.GetUnit("Infantryman");

            Console.ReadLine();
        }
    }
}
