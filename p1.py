# Clase para la tarea
class Tarea:
    def __init__(self, nombre, descripcion, prioridad):
        self.nombre = nombre
        self.descripcion = descripcion
        self.prioridad = prioridad

# Listas globales
tareas = []
completadas = []

# Agregar tarea
def preguntar_detalles():
    nombre = input("Ingrese el nombre de la tarea: ")
    descripcion = input("Ingrese la descripción de la tarea: ")
    prioridad = input("Ingrese la prioridad de la tarea: ")
    tarea = Tarea(nombre, descripcion, prioridad)
    tareas.append(tarea)  # 👈 se guarda en la lista
    print(f"Tarea '{nombre}' agregada con éxito.")

# Mostrar tareas pendientes
def resultado():
    if not tareas:
        print("No hay tareas pendientes.")
    else:
        print("\n--- LISTA DE TAREAS ---")
        for i, tarea in enumerate(tareas, start=1):
            print(f"{i}. {tarea.nombre} - {tarea.descripcion} - Prioridad: {tarea.prioridad}")

# Eliminar tarea
def eliminar_tarea():
    resultado()
    if tareas:
        num = int(input("Escribe el número de la tarea a eliminar: "))
        if 1 <= num <= len(tareas):
            eliminada = tareas.pop(num-1)
            print(f"Tarea '{eliminada.nombre}' eliminada.")
        else:
            print("Número inválido.")

# Completar tarea
def completar_tarea():
    resultado()
    if tareas:
        num = int(input("Escribe el número de la tarea a marcar como completada: "))
        if 1 <= num <= len(tareas):
            tarea = tareas.pop(num-1)
            completadas.append(tarea)
            print(f"Tarea '{tarea.nombre}' marcada como completada.")
        else:
            print("Número inválido.")

# Ver tareas completadas
def ver_completadas():
    if not completadas:
        print("No hay tareas completadas.")
    else:
        print("\n--- TAREAS COMPLETADAS ---")
        for i, tarea in enumerate(completadas, start=1):
            print(f"{i}. {tarea.nombre} - {tarea.prioridad}")

# Menú principal
def menu():
    while True:
        print("\n--- MENÚ DE TAREAS ---")
        print("1. Agregar tarea")
        print("2. Ver tareas")
        print("3. Eliminar tarea")
        print("4. Marcar tarea como completada")
        print("5. Ver tareas completadas")
        print("6. Salir")

        opcion = input("Elige una opción: ")

        if opcion == "1":
            preguntar_detalles()
        elif opcion == "2":
            resultado()
        elif opcion == "3":
            eliminar_tarea()
        elif opcion == "4":
            completar_tarea()
        elif opcion == "5":
            ver_completadas()
        elif opcion == "6":
            print(f"Saliendo... Pendientes: {len(tareas)}, Completadas: {len(completadas)}")
            break
        else:
            print("Opción no válida.")

        # 👇 Pregunta si el usuario quiere continuar o salir
        continuar = input("\n¿Quieres volver al menú? (s/n): ").lower()
        if continuar != "s":
            print("Programa terminado.")
            break

# Ejecutar el programa
menu()
