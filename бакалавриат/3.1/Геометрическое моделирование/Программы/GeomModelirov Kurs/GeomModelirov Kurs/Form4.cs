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
    public partial class Form4 : Form
    {
        public Form4(string title)
        {
            InitializeComponent();
            this.Text = title;
            Draw3();
        }

        private void pictureBox4_Click(object sender, EventArgs e)
        {

        }

        private void Draw3()
        {
            Bitmap bmp = new Bitmap(pictureBox4.Width, pictureBox4.Height);
            Graphics graph = Graphics.FromImage(bmp);
            Pen pen = new Pen(Color.Black);

            graph.DrawLine(pen, 90, 375, 125, 318);
            graph.DrawLine(pen, 90, 375, 103, 415);
            graph.DrawLine(pen, 90, 375, 90, 478);
            graph.DrawLine(pen, 125, 318, 125, 215);
            graph.DrawLine(pen, 125, 318, 326, 432);
            graph.DrawLine(pen, 125, 215, 180, 247);
            graph.DrawLine(pen, 125, 215, 236, 22);
            graph.DrawLine(pen, 236, 22, 290, 53);
            graph.DrawLine(pen, 290, 53, 290, 105);
            graph.DrawLine(pen, 290, 105, 355, 140);
            graph.DrawLine(pen, 290, 105, 180, 297);
            graph.DrawLine(pen, 180, 297, 180, 247);
            graph.DrawLine(pen, 180, 247, 290, 53);
            graph.DrawLine(pen, 180, 297, 269, 350);
            graph.DrawLine(pen, 269, 350, 269, 296);
            graph.DrawLine(pen, 269, 296, 326, 328);
            graph.DrawLine(pen, 269, 296, 376, 103);
            graph.DrawLine(pen, 355, 141, 290, 105);
            graph.DrawLine(pen, 376, 103, 434, 137);
            graph.DrawLine(pen, 434, 137, 434, 241);
            graph.DrawLine(pen, 434, 137, 326, 328);
            graph.DrawLine(pen, 326, 328, 326, 433);
            graph.DrawLine(pen, 326, 433, 125, 318);
            graph.DrawLine(pen, 434, 241, 467, 184);
            graph.DrawLine(pen, 467, 184, 467, 286);
            graph.DrawLine(pen, 467, 184, 455, 140);
            graph.DrawLine(pen, 455, 140, 306, 56);
            graph.DrawLine(pen, 306, 56, 290, 62);
            graph.DrawLine(pen, 467, 286, 293, 590);
            graph.DrawLine(pen, 293, 590, 293, 490);
            graph.DrawLine(pen, 293, 590, 250, 603);
            graph.DrawLine(pen, 293, 490, 326, 433);
            graph.DrawLine(pen, 293, 490, 250, 500);
            graph.DrawLine(pen, 250, 500, 250, 603);
            graph.DrawLine(pen, 250, 500, 103, 416);
            graph.DrawLine(pen, 103, 416, 103, 516);
            graph.DrawLine(pen, 103, 516, 250, 603);
            graph.DrawLine(pen, 103, 516, 90, 478);

            graph.DrawEllipse(pen, 180, 396, 42, 40);
            graph.DrawArc(pen, 340, 115, 56, 36, 180, 90);

            pictureBox4.Image = bmp;
        }
    }
}
