using Lab2._3.Fly;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._3.Ducks
{
    public class RubberDuck : DuckBase
    {
        public RubberDuck()
        {
            flyBehaviour = new NoFly();
        }

        public override void Display()
        {
            Console.WriteLine("Hi! I'm a rubber duck!");
        }
    }
}
