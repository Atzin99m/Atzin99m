using Newtonsoft.Json;
using Sesión;
using System;
using System.Net.Http;
using System.Text;
using Xamarin.Forms;

namespace Inicio_de_sesion
{
    public partial class MainPage : ContentPage
    {
        public MainPage()
        {
            InitializeComponent();
        }

        private void InitializeComponent()
        {
            throw new NotImplementedException();
        }

        public async void Start_Clicked(object sender, EventArgs e)
        {
            Login l = new Login
            {
                Email = EmailTxt,
                Password = PassTxt.Text,
                Ans = AnsTxt.Text
            };
            Uri RequestUri = new Uri("http://192.168.1.73/login/autent2.php");
            var client = new HttpClient();
            client.Timeout = TimeSpan.FromSeconds(200);
            var json = JsonConvert.SerializeObject(l);
            var contentJson = new StringContent(json, Encoding.UTF8, "application/json");
            try
            {
                var response = await client.PostAsync(RequestUri, contentJson);
                if (response.StatusCode == System.Net.HttpStatusCode.OK)
                {
                    var contents = await response.Content.ReadAsStringAsync();
                    Answert.Text = contents;
                    Login tmp = JsonConvert.DeserializeObject<Login>(contents);
                    if (tmp.Ans == "ok")
                    {
                        await Navigation.PushAsync(new Inicio());
                    }
                    else { await DisplayAlert("Mensaje", "Datos Inconrrectos", "ok"); }
                }
                else { await DisplayAlert("Mensaje", "Fallo de conexión", "ok"); }
            }
            catch (Exception error)
            {
                await DisplayAlert("Mensaje", error.Message, "ok");
            }
        }
    }
}
