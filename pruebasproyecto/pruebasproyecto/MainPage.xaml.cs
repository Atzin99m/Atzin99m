using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Essentials;
using Xamarin.Forms;

namespace pruebasproyecto
{
    public partial class MainPage : ContentPage
    {
        public MainPage()
        {
            InitializeComponent();
        }
		private async void bntAbrir_Clicked(object sender, EventArgs e)
		{
			if (!double.TryParse(txtLatitud.Text, out double lat))
				return;
			if (!double.TryParse(txtLongitud.Text, out double lng))
				return;
			await Map.OpenAsync(lat, lng, new MapLaunchOptions
			{
				Name = txtNombreUbica.Text,
				NavigationMode = NavigationMode.None

			}); 

		}
	}
}
