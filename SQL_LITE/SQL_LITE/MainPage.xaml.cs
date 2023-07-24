using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using SQL_LITE.Modelos;
using SQL_LITE.Datos;
using Xamarin.Forms;

namespace SQL_LITE
{
    public partial class MainPage : ContentPage
    {
        public MainPage()
        {
            InitializeComponent();
        }

        private  async void Insert_Clicked(object sender, EventArgs e)
        {
            if (true)
            {
                alumnos students = new alumnos
                {
                    nombre = Name.Text,
                    apellidosp = LastName.Text,
                    apellidosm = LastNameM.Text,
                    correo = Email.Text
                };

                await App.SQLiteDB.Guardaralumno(students);
                Name.Text = "";
                LastName.Text = "";
                LastNameM.Text = "";
                Email.Text = "";
                await DisplayAlert("Message", "El alumno ya esta registrado exitosamente", "Aceptar");
                var listado = await App.SQLiteDB.Getalumnos();
                if (listado==null)
                {
                    listst.ItemsSource = listado;
                }
                else
                {
                    await DisplayAlert("Message", "Ingrese por favor toda la información completa", "Aceptar");
                }
                validates();
            }
        }
        public bool validates()
        {
            bool resp;
            if (string.IsNullOrEmpty(Name.Text))
            {
                resp = false;
            }
         else if (string.IsNullOrEmpty(LastName.Text))
            {
                resp = false;
            }
            else if (string.IsNullOrEmpty(LastNameM.Text))
            {
                resp = false;
            }
            else if (string.IsNullOrEmpty(Email.Text))
            {
                resp = false;
            }
            else
            {
                resp = true;
                            } return resp;
        }
    }
}
