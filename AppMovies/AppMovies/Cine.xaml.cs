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
    public partial class Cine : ContentPage
    {
        public Cine()
        {
            InitializeComponent();
        }
        private async void Soundtracks(System.Object sender, System.EventArgs e) { await Navigation.PushAsync(new Soundtracks()); }
        private async void Cines(System.Object sender, System.EventArgs e) { await Navigation.PushAsync(new Cines()); }
        private async void Peliculas(System.Object sender, System.EventArgs e) { await Navigation.PushAsync(new Pelicula()); }
        private async void Funciones(System.Object sender, System.EventArgs e) { await Navigation.PushAsync(new Funciones()); }
        private async void Eventos(System.Object sender, System.EventArgs e) { await Navigation.PushAsync(new Eventos()); }
        private async void Preestrenos(System.Object sender, System.EventArgs e) { await Navigation.PushAsync(new Preestrenos()); }
        private async void Informacion(System.Object sender, System.EventArgs e) { await Navigation.PushAsync(new Informacion()); }
        public async void salir_Clicked(object sender, EventArgs e)
        {
            if ((Application.Current.Properties.ContainsKey("Correo")) & (Application.Current.Properties.ContainsKey("Contraseña")))
            {
                Application.Current.Properties.Remove("Correo");
                Application.Current.Properties.Remove("Contraseña");
                await Navigation.PopToRootAsync(false);
              await Navigation.PushAsync(new MainPage(), true);
              //  await Application.Current.MainPage.Navigation.PopAsync();

            }

        }
    }
}















