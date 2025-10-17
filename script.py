a = 0
b = 1

for _ in range(20):  # puedes ajustar el rango según cuántos quieras
    if a % 2 == 0:
        print(a)
    a, b = b, a + b
    # Actualiza a y b para la siguiente iteración