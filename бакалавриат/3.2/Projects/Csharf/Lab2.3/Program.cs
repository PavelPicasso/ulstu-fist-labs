using Lab2._3.Ducks;
using Lab2._3.Fly;
using Lab2._3.Quack;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._3
{
    class Program
    {
        static void Main(string[] args)
        {
            List<DuckBase> ducks = new List<DuckBase>();
            ducks.Add(new ExoticDuck());
            ducks.Add(new SimpleDuck());
            ducks.Add(new WoodenDuck());
            ducks.Add(new RubberDuck());

            foreach (var duck in ducks)
            {
                duck.Display();
                duck.Swim();
                duck.Quack();
                duck.Fly();

                Console.WriteLine();
            }

            DuckBase upgradableDuck = new UpgradableDuck();
            upgradableDuck.Display();
            upgradableDuck.Swim();
            upgradableDuck.Quack();
            upgradableDuck.Fly();

            upgradableDuck.SetFlyBehaviour(new FlyWithWings());
            upgradableDuck.SetQuackBehaviour(new ExoticQuack());
            upgradableDuck.Quack();
            upgradableDuck.Fly();

            Console.ReadKey();
        }
    }
}
