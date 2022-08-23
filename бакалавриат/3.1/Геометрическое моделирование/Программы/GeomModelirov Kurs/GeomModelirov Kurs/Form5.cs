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
    public partial class Form5 : Form
    {
        public Form5(string title)
        {
            InitializeComponent();
            this.Text = title;
            Draw4();
        }

        private void pictureBox5_Click(object sender, EventArgs e)
        {

        }

        private void Draw4()
        {
            Bitmap bmp = new Bitmap(pictureBox5.Width, pictureBox5.Height);
            Graphics graph = Graphics.FromImage(bmp);
            Pen pen = new Pen(Color.Black);

            graph.DrawLine(pen, 60, 277, 60, 182);
            graph.DrawLine(pen, 60, 277, 78, 292);
            graph.DrawLine(pen, 78, 292, 78, 213);
            graph.DrawLine(pen, 78, 292, 104, 307);
            graph.DrawLine(pen, 104, 307, 104, 228);
            graph.DrawLine(pen, 104, 307, 402, 307);
            graph.DrawLine(pen, 402, 307, 402, 228);
            graph.DrawLine(pen, 402, 307, 426, 293);
            graph.DrawLine(pen, 426, 293, 426, 210);
            graph.DrawLine(pen, 426, 210, 402, 228);
            graph.DrawLine(pen, 426, 210, 400, 182);
            graph.DrawLine(pen, 400, 182, 380, 175);
            graph.DrawLine(pen, 380, 175, 341, 175);
            graph.DrawLine(pen, 402, 228, 341, 228);
            graph.DrawLine(pen, 341, 228, 341, 160);
            graph.DrawLine(pen, 341, 160, 177, 160);
            graph.DrawLine(pen, 341, 160, 326, 136);
            graph.DrawLine(pen, 326, 136, 158, 136);
            graph.DrawLine(pen, 158, 136, 177, 160);
            graph.DrawLine(pen, 177, 160, 177, 228);
            graph.DrawLine(pen, 158, 136, 158, 172);
            graph.DrawLine(pen, 158, 172, 137, 151);
            graph.DrawLine(pen, 137, 151, 158, 151);
            graph.DrawLine(pen, 137, 151, 137, 111);
            graph.DrawLine(pen, 137, 111, 296, 111);
            graph.DrawLine(pen, 137, 111, 120, 93);
            graph.DrawLine(pen, 296, 111, 296, 136);
            graph.DrawLine(pen, 296, 111, 281, 93);
            graph.DrawLine(pen, 281, 93, 120, 93);
            graph.DrawLine(pen, 120, 93, 120, 171);
            graph.DrawLine(pen, 120, 171, 177, 228);
            graph.DrawLine(pen, 177, 228, 104, 228);
            graph.DrawLine(pen, 104, 228, 78, 213);
            graph.DrawLine(pen, 78, 213, 60, 182);
            graph.DrawLine(pen, 60, 182, 90, 172);
            graph.DrawLine(pen, 90, 172, 120, 172);

            graph.DrawEllipse(pen, 97, 187, 20, 18);
            graph.DrawEllipse(pen, 358, 189, 20, 18);

            pictureBox5.Image = bmp;
        }
    }
}
