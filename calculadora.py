import math

def sumar(a, b):
  return a + b

def restar(a, b):
  return a - b

def multiplicar(a, b):
  return a * b

def dividir(a, b):
  return a / b
def raiz_cuadrada(a):
  return math.sqrt(a)

def potencia(a, b):
  return math.pow(a, b)

def residuo(a, b):
  return math.fmod(a, b)

def fraccion(a, b):
  return a / b
while True:
  print("Selecciona operación.")
  print("1. Sumar")
  print("2. Restar")
  print("3. Multiplicar")
  print("4. Dividir")
  print("5. Raíz cuadrada")
  print("6. Potencia")
  print("7. Residuo")
  print("8. Fracción")

  operacion = input("Selecciona operación(1/2/3/4/5/6/7/8): ")

  if operacion in ('1', '2', '3', '4', '5', '6', '7', '8'):
    num1 = float(input("Ingresa el primer número: "))
    num2 = float(input("Ingresa el segundo número: "))

    if operacion == '1':
      print(num1, "+", num2, "=", sumar(num1, num2))

    elif operacion == '2':
      print(num1, "-", num2, "=", restar(num1, num2))

    elif operacion == '3':
      print(num1, "*", num2, "=", multiplicar(num1, num2))

    elif operacion == '4':
      print(num1, "/", num2, "=", dividir(num1, num2))

    elif operacion == '5':
      print(num1, "sqrt", "=", raiz_cuadrada(num1))

    elif operacion == '6':
      print(num1, "^", num2, "=", potencia(num1, num2))

    elif operacion == '7':
      print(num1, "%", num2, "=", residuo(num1, num2))

    elif operacion == '8':
      print(num1, "/", num2, "=", fraccion(num1, num2))

  else:
    print("¡Entrada inválida!")

  eleccion = input("¿Continuar con la operación? (Y/N): ")
  if eleccion == "N":
    break
