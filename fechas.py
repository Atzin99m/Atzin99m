from datetime import datetime, timedelta

# Fecha de inicio (hoy)
hoy = datetime.today()

# Generar lista de fechas para los próximos 30 días
fechas = [hoy + timedelta(days=i) for i in range(30)]

# Filtrar solo sábados (5) y domingos (6)
fines_de_semana = [fecha.strftime("%Y-%m-%d") for fecha in fechas if fecha.weekday() in (5, 6)]

# Mostrar resultado
print(fines_de_semana)