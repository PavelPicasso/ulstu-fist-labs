using Lab2._4.Warriors;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._4.Factory
{
    class SimpleFactory
    {
        public Warrior GetUnit(string type)
        {
            Warrior warrior = new Warrior();

            if (type == "Horseman")
                warrior = new AllianHorseman();
            else if (type == "Archer")
                warrior = new AllianceArcher();
            else if (type == "Infantryman")
                warrior = new AllianInfantryman();

            return warrior;
        }
    }
}
