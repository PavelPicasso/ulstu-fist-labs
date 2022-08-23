using Lab2._4.Warriors;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._4.Facilities
{
    class Horde : Info
    {
        public override Warrior CreateUnit(string type)
        {
            Warrior warrior = new Warrior();

            if (type == "Horseman")
                warrior = new HordeHorseman();
            else if (type == "Archer")
                warrior = new HordeArcher();
            else if (type == "Infantryman")
                warrior = new HordeInfantryman();

            return warrior;
        }
    }
}
