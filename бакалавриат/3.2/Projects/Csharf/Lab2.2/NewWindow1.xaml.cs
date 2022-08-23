using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Animation;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;

namespace Lab2._2
{

    /// <summary>
    /// Логика взаимодействия для NewWindow1.xaml
    /// </summary>
    public partial class NewWindow1 : Window
    {
        public NewWindow1(string title)
        {
            InitializeComponent();

            DoubleAnimation da = new DoubleAnimation();
            da.From = 0;
            da.To = 300;
            da.Duration = TimeSpan.FromSeconds(2);
            BeginAnimation(NewWindow1.HeightProperty, da);
            Title = title;
            textBox.Text = Convert.ToString(Lab2._2.MainWindow.it++);
        }

        private void textBox_TextChanged(object sender, TextChangedEventArgs e)
        {

        }

        private void Animation_Completed(object sender, EventArgs e)
        {
            this.Hide();
        }

        private void button_Click(object sender, RoutedEventArgs e)
        {
            if (comboBox.Text == "Actor")
            {
                Lab2._2.Lists.AddActor(Convert.ToInt32(textBox.Text), Convert.ToInt32(textBox1.Text), textBox2.Text, textBox3.Text);
            }
            else
            {
                Lab2._2.Lists.AddTest(Convert.ToInt32(textBox.Text), Convert.ToInt32(textBox4.Text));
            }
            DoubleAnimation da1 = new DoubleAnimation();
            da1.To = 0;
            da1.Duration = TimeSpan.FromSeconds(2);
            da1.Completed += Animation_Completed;
            BeginAnimation(NewWindow1.HeightProperty, da1);
        }

        private void comboBox_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {

        }
    }
}
