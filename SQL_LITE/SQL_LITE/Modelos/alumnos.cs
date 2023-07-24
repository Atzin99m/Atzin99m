using System;
using System.Collections.Generic;
using System.Text;
using SQLite;
namespace SQL_LITE.Modelos
{
    class alumnos
    {
        [PrimaryKey, AutoIncrement]
        public int matricula { get; set; } 
        [MaxLength(30)]
        public string nombre { get; set; }
        [MaxLength(30)]
        public string apellidosp { get; set; }
        [MaxLength(30)]
        public string apellidosm { get; set; }
        [MaxLength(50)]
        public string correo { get; set; }
    }
}
