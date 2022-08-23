using Lab2._3.Fly;
using Lab2._3.Quack;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Lab2._3.Ducks
{
    public abstract class DuckBase
    {
        protected IFlyable flyBehaviour;
        protected IQuackable quackBehaviour;

        public DuckBase()
        {
            flyBehaviour = new FlyWithWings();
            quackBehaviour = new SimpleQuack();
        }

        public void SetQuackBehaviour(IQuackable newQuackBehaviour)
        {
            quackBehaviour = newQuackBehaviour;
        }

        public void SetFlyBehaviour(IFlyable newFlyBehaviour)
        {
            flyBehaviour = newFlyBehaviour;
        }

        public void Swim()
        {
            Console.WriteLine("I'm swimming");
        }

        public void Quack()
        {
            quackBehaviour.Quack();
        }

        public void Fly()
        {
            flyBehaviour.Fly();
        }

        public abstract void Display();
    }
}
