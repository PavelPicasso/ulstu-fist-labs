using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace GeomModelirov_Kurs
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
            this.Text = "Window";
        }

        private void Form1_Load(object sender, EventArgs e)
        {

        }

        private void button1_Click(object sender, EventArgs e)
        {
            Form2 window = new Form2("Изометрическая");
            window.ShowDialog();
        }

        private void button2_Click(object sender, EventArgs e)
        {
            Form3 window = new Form3("Диаметрическая");
            window.ShowDialog();
        }

        private void button3_Click(object sender, EventArgs e)
        {
            Form4 window = new Form4("Косоугольная горизонтальная");
            window.ShowDialog();
        }

        private void button4_Click(object sender, EventArgs e)
        {
            Form5 window = new Form5("Косоугольная фронтальная");
            window.ShowDialog();
        }
    }
}
