using System;
using System.Collections.Generic;
using System.Text;
using SQLite;
using SQL_LITE.Modelos;
using System.Threading.Tasks;

namespace SQL_LITE.Datos
{

    public class operations
    {
        SQLiteAsyncConnection db;
        public operations(string dbpath)
        {
            db = new SQLiteAsyncConnection(dbpath);
            db.CreateTableAsync<alumnos>().Wait();
        }
        public Task<int> Guardaralumno(alumnos alm)
        {
            if (alm.matricula == 0)
            {
                return db.InsertAsync(alm);
            }
            else { return null; }
        }
       public Task <List<alumnos>> Getalumnos(){
        return db.Table<alumnos>().ToListAsync();
    }
    }
}
