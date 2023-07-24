using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace AppMovies
{
    [XamlCompilation(XamlCompilationOptions.Compile)]
    public partial class Pelicula : ContentPage
    {
        serviceweb conexion = new serviceweb();
        public Pelicula()
        {
            InitializeComponent();
            readyprox();
        }
        public void readyprox()
        {
            webview.IsVisible = false;
            webview.Source = conexion.dominio + "movies.php";
        }
        private void searchbar_SearchButtonPressed(object sender, EventArgs e)
        {

        }
        async void webview_navigating(object sender, WebNavigatingEventArgs e)
        {
            indicador.IsRunning = true;
            indicador.IsEnabled = true;
            var httpClient = new HttpClient();
                httpClient.Timeout = TimeSpan.FromSeconds(100);

            try
            {
                
                var response = await httpClient.GetAsync(conexion.dominio + "movies.php");
                if (response.IsSuccessStatusCode)
                {
                    webview.IsVisible = true;
                }
                else
                {
                    await DisplayAlert("Mensaje", "Sin servicio, Intente más tarde", "ok");
                    await Application.Current.MainPage.Navigation.PopAsync();


                }
            }
            catch (Exception inf)
            {
                await DisplayAlert("Mensaje", "Sin servicio, Intente más tarde "+ " "+ inf.Message, "ok");
                await Application.Current.MainPage.Navigation.PopAsync();
            }
        }

        async void webview_navigated(object sender, WebNavigatedEventArgs e)
        {
            label1.IsVisible = false;
            indicador.IsRunning = false;
            vistaopacada.IsVisible = false;
        }
    }
}
