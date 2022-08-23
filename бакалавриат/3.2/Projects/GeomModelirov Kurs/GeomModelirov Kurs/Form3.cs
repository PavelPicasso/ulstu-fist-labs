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
    public partial class Form3 : Form
    {
        public Form3(string title)
        {
            InitializeComponent();
            this.Text = title;
            Draw2();
        }

        private void Form3_Load(object sender, EventArgs e)
        {

        }

        private void Draw2()
        {
            Bitmap bmp = new Bitmap(pictureBox2.Width, pictureBox2.Height);
            Graphics graph = Graphics.FromImage(bmp);
            Pen pen = new Pen(Color.Black);

            graph.DrawLine(pen, 16, 149, 31, 137);
            graph.DrawLine(pen, 16, 149, 70, 200);
            graph.DrawLine(pen, 16, 149, 16, 237);
            graph.DrawLine(pen, 16, 237, 70, 288);
            graph.DrawLine(pen, 70, 288, 70, 200);
            graph.DrawLine(pen, 70, 288, 106, 295);
            graph.DrawLine(pen, 106, 295, 106, 206);
            graph.DrawLine(pen, 106, 206, 70, 200);
            graph.DrawLine(pen, 106, 206, 164, 198);
            graph.DrawLine(pen, 106, 295, 417, 256);
            graph.DrawLine(pen, 417, 256, 417, 167);
            graph.DrawLine(pen, 417, 256, 434, 244);
            graph.DrawLine(pen, 434, 244, 434, 155);
            graph.DrawLine(pen, 434, 155, 417, 167);
            graph.DrawLine(pen, 417, 167, 360, 175);
            graph.DrawLine(pen, 360, 175, 360, 85);
            graph.DrawLine(pen, 434, 155, 380, 104);
            graph.DrawLine(pen, 380, 104, 360, 100);
            graph.DrawLine(pen, 360, 85, 339, 66);
            graph.DrawLine(pen, 360, 85, 164, 111);
            graph.DrawLine(pen, 164, 111, 164, 198);
            graph.DrawLine(pen, 164, 198, 90, 130);
            graph.DrawLine(pen, 90, 130, 31, 137);
            graph.DrawLine(pen, 339, 66, 144, 90);
            graph.DrawLine(pen, 144, 90, 144, 135);
            graph.DrawLine(pen, 144, 90, 164, 111);
            graph.DrawLine(pen, 144, 135, 111, 105);
            graph.DrawLine(pen, 111, 105, 111, 59);
            graph.DrawLine(pen, 111, 105, 143, 100);
            graph.DrawLine(pen, 111, 59, 306, 36);
            graph.DrawLine(pen, 306, 36, 306, 71);
            graph.DrawLine(pen, 306, 36, 285, 16);
            graph.DrawLine(pen, 285, 16, 90, 41);
            graph.DrawLine(pen, 90, 41, 90, 130);
            graph.DrawLine(pen, 90, 41, 111, 59);

            graph.DrawEllipse(pen, 85, 165, 18, 7);
            graph.DrawArc(pen, 360, 134, 3, 4, -90, 180);

            pictureBox2.Image = bmp;

        }

        private void pictureBox2_Click(object sender, EventArgs e)
        {

        }
    }
}
