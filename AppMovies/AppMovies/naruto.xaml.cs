using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace AppMovies
{
    [XamlCompilation(XamlCompilationOptions.Compile)]
    public partial class naruto : ContentPage
    {
        public naruto()
        {
            InitializeComponent();
             var htmlcontent = new HtmlWebViewSource();
          htmlcontent.Html = "<html><head> </head><body>" +
              "<iframe width='568' height='315' src='https://youtu.be/B7urhcxQz38' " +
      "frameborder='0' allow='autoplay; encrypted-media' allowfullscreen></iframe>" +
      "</body></html>";
          TrailerNaruto.Source = htmlcontent; 
        }
    }
}