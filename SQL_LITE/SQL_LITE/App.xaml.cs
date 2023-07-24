using System;
using Xamarin.Forms;
using Xamarin.Forms.Xaml;
using SQL_LITE.Modelos;
using SQL_LITE.Datos;
using System.IO;

namespace SQL_LITE
{
    public partial class App : Application
    {
        public static operations db;
        public App()
        {
            InitializeComponent();

            MainPage = new MainPage();
        }

        public static operations SQLiteDB
        {
            get
            {
                if (db == null)
                {
                    db = new operations(Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData), "upvm.db3"));
                }
                return db;
            } 
            
        }

       
        protected override void OnStart()
        {
        }

        protected override void OnSleep()
        {
        }

        protected override void OnResume()
        {
        }
    }
}
