using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Windows;

namespace GeomModelirov_Kurs
{
    public partial class Form2 : Form
    {
        public Form2(string title)
        {
            InitializeComponent();
            this.Text = title;
            Draw1();
        }

        private void Form2_Load(object sender, EventArgs e)
        {

        }

        private void Draw1() {
            Bitmap bmp = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            Graphics graph = Graphics.FromImage(bmp);
            Pen pen = new Pen(Color.Black);

            graph.DrawLine(pen, 30, 230, 30, 255);
            graph.DrawLine(pen, 30, 230, 73, 205);
            graph.DrawLine(pen, 73, 205, 73, 126);
            graph.DrawLine(pen, 73, 205, 227, 293);
            graph.DrawLine(pen, 30, 255, 143, 318);
            graph.DrawLine(pen, 143, 318, 183, 319);
            graph.DrawLine(pen, 183, 319, 227, 293);
            graph.DrawLine(pen, 227, 293, 227, 216);
            graph.DrawLine(pen, 227, 216, 184, 191);
            graph.DrawLine(pen, 184, 191, 184, 229);
            graph.DrawLine(pen, 184, 229, 116, 192);
            graph.DrawLine(pen, 116, 192, 116, 152);
            graph.DrawLine(pen, 116, 152, 73, 126);
            graph.DrawLine(pen, 73, 126, 220, 43);
            graph.DrawLine(pen, 220, 43, 263, 68);
            graph.DrawLine(pen, 263, 68, 116, 152);
            graph.DrawLine(pen, 263, 68, 263, 107);
            graph.DrawLine(pen, 263, 107, 116, 192);
            graph.DrawLine(pen, 263, 107, 297, 126);
            graph.DrawLine(pen, 264, 96, 305, 96);
            graph.DrawLine(pen, 305, 96, 326, 109);
            graph.DrawLine(pen, 330, 107, 184, 191);
            graph.DrawLine(pen, 330, 107, 373, 131);
            graph.DrawLine(pen, 373, 131, 227, 216);
            graph.DrawLine(pen, 373, 131, 373, 209);
            graph.DrawLine(pen, 373, 209, 417, 184);
            graph.DrawLine(pen, 417, 184, 417, 160);
            graph.DrawLine(pen, 417, 160, 374, 136);
            graph.DrawLine(pen, 417, 184, 417, 261);
            graph.DrawLine(pen, 417, 261, 183, 395);
            graph.DrawLine(pen, 183, 395, 183, 318);
            graph.DrawLine(pen, 183, 395, 143, 395);
            graph.DrawLine(pen, 143, 395, 143, 318);
            graph.DrawLine(pen, 143, 395, 30, 330);
            graph.DrawLine(pen, 30, 330, 30, 255);

            graph.DrawEllipse(pen, 116, 259, 21, 12);

            pictureBox1.Image = bmp;

        }

        private void pictureBox1_Click(object sender, EventArgs e)
        {

        }
    }
}
