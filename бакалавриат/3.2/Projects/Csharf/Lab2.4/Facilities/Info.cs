using Lab2._4.Warriors;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._4.Facilities
{
    abstract class Info
    {
        public Warrior GetUnit(string type)
        {
            Warrior warrior = CreateUnit(type);

            warrior.Configure();

            return warrior;
        }

        public abstract Warrior CreateUnit(string type);
    }
}
