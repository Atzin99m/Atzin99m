
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;
using static Xamarin.Essentials.Permissions;

namespace AppMovies
{
    [XamlCompilation(XamlCompilationOptions.Compile)]
    public partial class Soundtracks : ContentPage
    {
        public Soundtracks()
        {
            InitializeComponent();
           ;
        }
        public async void Venom_Clicked(object sender, EventArgs e)
        {
            await Navigation.PushAsync(new venom ());
        
        }

        private async void Cruella_Clicked(object sender, EventArgs e)
        {
            await Navigation.PushAsync(new cruella());
            
        }

        private async void RF_Clicked(object sender, EventArgs e)
        {
            await Navigation.PushAsync(new rf());
         
        }

        private async void Conjuro_Clicked(object sender, EventArgs e)
        {
            await Navigation.PushAsync(new conjuro());
          
        }

        private async void Naruto_Clicked(object sender, EventArgs e)
        {
            await Navigation.PushAsync(new naruto());
         
        }
         private async void Mitchells_Clicked(object sender, EventArgs e)
         {
            await Navigation.PushAsync(new mitchells());
            
          
        }
    }
}