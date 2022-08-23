using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._3.Fly
{
    public class NoFly : IFlyable
    {
        public void Fly()
        {
            Console.WriteLine("---");
        }
    }
}
