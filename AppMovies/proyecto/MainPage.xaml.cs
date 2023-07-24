using System;
using System.Net;
using System.Net.Http;
using Newtonsoft.Json;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace AppMovies
{
    public partial class MainPage : ContentPage
    {
        public MainPage()
        {
            InitializeComponent();
        }
        public async void Start_Clicked(object sender, EventArgs e)
        {
            entradas l = new entradas
            {
                Email = EmailTxt.Text,
                pass = PassTxt.Text,
                ans = AnsTxt.Text
            }; 
               
                if (string.IsNullOrEmpty(EmailTxt.Text) || string.IsNullOrEmpty(PassTxt.Text))
                {
                    await DisplayAlert("Mensaje", "Ingrese correo y/o contraseña", "Aceptar");
                    EmailTxt.Focus();
                    PassTxt.Focus();
                }


                Uri RequestUri = new Uri("http://192.168.1.73/Cine/php/auntetn2.php");
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
                    entradas tmp = JsonConvert.DeserializeObject<entradas>(contents);
                    if (tmp.ans == "ok")
                    {
                        //Guardar la Data en el dispositivo después de ingresar corectamente
                        Application.Current.Properties["Correo"] = EmailTxt;
                        Application.Current.Properties["Contraseña"] = PassTxt;
                        await Application.Current.SavePropertiesAsync();
                        //Entra a inicio
                        await Navigation.PushAsync(new Cine());

                    }
                    else
                    {
                        await DisplayAlert("Mensaje", "Datos Inconrrectos", "ok");
                    }
                }
                else { await DisplayAlert("Mensaje", "Fallo de conexión", "ok"); }
            }
            catch (Exception error)
            {
                await DisplayAlert("Mensaje", error.Message, "ok");
            }
        }
       /* public Action CustomBackButtonAction { get; set; }

        public static readonly BindableProperty EnableBackButtonOverrideProperty =
BindableProperty.Create(
nameof(EnableBackButtonOverride),
typeof(bool),
typeof(MainPage),
false);

        public bool EnableBackButtonOverride
        {
            get
            {
                return (bool)GetValue(EnableBackButtonOverrideProperty);
            }
            set
            {
                SetValue(EnableBackButtonOverrideProperty, value);
            }
        }*/
    }
}
