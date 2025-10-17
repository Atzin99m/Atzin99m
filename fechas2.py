import calendar
import matplotlib.pyplot as plt
from matplotlib.backends.backend_pdf import PdfPages

# Parámetros
anio = 2026

# Días festivos (ejemplo)
dias_festivos = {
    1: [1],        # Año Nuevo
    3: [21],       # Día festivo en marzo
    5: [1],        # Día del trabajo
    9: [16],       # Independencia
    12: [25]       # Navidad
}

# Colores
colores = {"Festivo": "red", "No laborable": "gray", "Laboral": "green"}

# Crear PDF
with PdfPages("calendario_2026.pdf") as pdf:
    for mes in range(1, 13):
        cal = calendar.monthcalendar(anio, mes)
        fig, ax = plt.subplots(figsize=(8, 6))
        ax.set_title(f"{calendar.month_name[mes]} {anio}", fontsize=16)

        # Generar lista de días con list comprehension
        dias_info = [
            (dia, fila, col,
             colores["Festivo"] if dia in dias_festivos.get(mes, []) else
             colores["No laborable"] if col >= 5 else
             colores["Laboral"])
            for fila, semana in enumerate(cal)
            for col, dia in enumerate(semana)
            if dia != 0
        ]

        # Dibujar días
        for dia, fila, col, color in dias_info:
            ax.add_patch(plt.Rectangle((col, -fila), 1, 1, color=color, alpha=0.6))
            ax.text(col + 0.5, -fila + 0.5, str(dia), ha="center", va="center", fontsize=12)

        # Ajustes
        ax.set_xlim(0, 7)
        ax.set_ylim(-len(cal), 0)
        ax.set_xticks(range(7))
        ax.set_xticklabels(["L", "M", "X", "J", "V", "S", "D"])
        ax.axis("off")

        pdf.savefig(fig)
      
plt.show()
print("✅ Calendario anual generado en calendario_2025.pdf")