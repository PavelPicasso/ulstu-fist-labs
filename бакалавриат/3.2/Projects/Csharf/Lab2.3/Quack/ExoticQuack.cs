using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._3.Quack
{
    public class ExoticQuack : IQuackable
    {
        public void Quack()
        {
            Console.WriteLine("Quicks! Quicks!");
        }
    }
}
