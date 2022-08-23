using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._4.Warriors
{
    class Warrior
    {
        protected string Name = "[]";
        protected string Attack = "0";
        protected string Armor = "0";
        protected string HP = "0";
        protected string MP = "0";

        public void Configure()
        {
            Console.WriteLine("Info {0}", Name);
            Console.WriteLine("Health Points {0}", HP);
            Console.WriteLine("Mana Points {0}", MP);
            Console.WriteLine("Attack {0}", Attack);
            Console.WriteLine("Armor {0}", Armor);
        }
    }
}
