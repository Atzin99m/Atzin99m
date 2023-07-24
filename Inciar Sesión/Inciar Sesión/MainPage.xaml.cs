using Newtonsoft.Json;
using System;
using System.Net.Http;
using System.Text;
using Xamarin.Forms;

namespace Inciar_Sesión
{
    public partial class MainPage : ContentPage
    {
        public MainPage()
        {
            InitializeComponent();
        }
        protected override void OnAppearing()
        {
            base.OnAppearing();
          
            //buscar propiedades
            if (Application.Current.Properties.ContainsKey("Correo")) { 
                EmailTxt.Text = (string)Application.Current.Properties["Correo"]; }
            if (Application.Current.Properties.ContainsKey("Contraseña"))
            {
                PassTxt.Text = (string)Application.Current.Properties["Contraseña"];
            }
        }
        public async void Start_Clicked(object sender, EventArgs e)
        {
            login l = new login
            {
                Email = EmailTxt.Text,
                Password = PassTxt.Text,
                Ans = AnsTxt.Text
            };
            Uri RequestUri = new Uri("http://192.168.1.73/login/autetn2.php");
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
                    AnsTxt.Text = contents;
                    login tmp = JsonConvert.DeserializeObject<login>(contents);
                    if (tmp.Ans == "ok")
                    {
                        //Guardar la Data en el dispositivo después de ingresar corectamente
                        Application.Current.Properties["Correo"] = EmailTxt;
                        Application.Current.Properties["Contraseña"] = PassTxt;
                        await Application.Current.SavePropertiesAsync();
                        //Entra a inicio
                        await Navigation.PushAsync(new Inicio());
                        
                    }
                    else { await DisplayAlert("Mensaje", "Datos Inconrrectos", "ok"); 
                    }
                }
                else { await DisplayAlert("Mensaje", "Fallo de conexión", "ok"); }
            }
            catch (Exception error)
            {
                await DisplayAlert("Mensaje", error.Message, "ok");
            }
        }

        public async void Close_Clicked(object sender, EventArgs e) {
            if (Application.Current.Properties.ContainsKey("Correo"))
            {
                Application.Current.Properties.Remove("Correo");
            }
            if (Application.Current.Properties.ContainsKey("Contraseña"))
            {
                Application.Current.Properties.Remove("Contraseña");
            }
            await Navigation.PopToRootAsync(false);
            await Navigation.PushAsync(new Inicio(), true);
        }

    }
}
