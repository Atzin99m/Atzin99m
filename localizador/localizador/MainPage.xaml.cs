using Plugin.Geolocator;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace localizador
{
    public partial class MainPage : ContentPage
    {
        public MainPage()
        {
            InitializeComponent();
        }

        private async void yosoyelmapa(object sender, EventArgs e)
        {
            var l = CrossGeolocator.Current;
            if (l.IsGeolocationAvailable) { if (l.IsGeolocationEnabled) { if (!l.IsListening) {
                        await l.StartListeningAsync(TimeSpan.FromSeconds(1, 5));
                            } } }
        }
    }
}
