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
using System.Windows.Navigation;
using System.Windows.Shapes;

namespace Lab2._2
{
    /// <summary>
    /// Логика взаимодействия для MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        public static int it = 0;
        public MainWindow()
        {
            InitializeComponent();
        }
        private void textBox_TextChanged(object sender, TextChangedEventArgs e)
        {

        }

        private void button_Click(object sender, RoutedEventArgs e)
        {
            NewWindow1 window = new NewWindow1("Modal");
            window.ShowDialog();
            textBox.Clear();
        }

        private void textBox_TextChanged_1(object sender, TextChangedEventArgs e)
        {

        }

        private void button3_Click(object sender, RoutedEventArgs e)
        {
            textBox.Clear();
            textBox.Text = Lab2._2.Lists.ListActor();
        }

        private void button4_Click(object sender, RoutedEventArgs e)
        {
            textBox.Clear();
            textBox.Text = Lab2._2.Lists.ListTest();
        }

        private void textBox_TextChanged_2(object sender, TextChangedEventArgs e)
        {

        }

        private void button1_Click(object sender, RoutedEventArgs e)
        {
            textBox.Clear();
            if (comboBox.Text == "Actor")
            {
                textBox.Text = Lab2._2.Lists.DeleteActor(Convert.ToInt32(textBox1.Text));
            }
            else
            {
                textBox.Text = Lab2._2.Lists.DeleteTest(Convert.ToInt32(textBox1.Text));
            }
        }

        private void textBox1_TextChanged(object sender, TextChangedEventArgs e)
        {

        }

        private void comboBox_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {

        }

        private void button2_Click(object sender, RoutedEventArgs e)
        {
            EditWindow window = new EditWindow("Modal");
            window.ShowDialog();
            textBox.Clear();
        }
    }
}
