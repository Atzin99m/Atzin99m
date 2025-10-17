import random

# Lista completa de materias
materias = [
    'Math', 'CompSci', 'Art', 'Biology', 'I.T.', 'music', 'Sports',
    'Etics, Morality & laws', 'History', 'Geography', 'Physics', 'Chemistry',
    'Philosophy', 'Psychology', 'Sociology', 'Economics', 'Business Studies',
    'Accounting', 'Marketing', 'Finance', 'Management Studies', 'Law',
    'Medicine', 'Nursing', 'Pharmacy', 'Dentistry', 'Veterinary Science',
    'Architecture', 'Engineering', 'Environmental Science', 'Astronomy',
    'Geology', 'Anthropology', 'Linguistics', 'Literature', 'Creative Writing',
    'Drama & Theatre Studies', 'Film Studies', 'Media Studies',
    'Production , Engineering musical', 'theatre', 'Dance', 'Photography',
    'Graphic Design', 'Acting'
]

# Lista de nombres de alumnos
nombres = ['Alice', 'Hernan', 'Carlos', 'Diana', 'Eva', 'Roberto', 
           'Gabriela', 'Hugo', 'Isabel', 'Jorge', 'Atzin Mauricio','Melanie','Brandon',
           'Nancy','Oscar','Juan','Leslie','Rosa','Berenice','Tomas']

# Diccionario grande con todos los alumnos
alumnos_dict = {}

# Generar datos para cada alumno
for nombre in nombres:
    edad = random.randint(18, 30)
    materias_seleccionadas = random.sample(materias, k=random.randint(5, 10))
    promedios = []
    notas = []
    for materia in materias_seleccionadas:
        nota = random.randint(50, 100)
        promedios.append({materia: nota})
        notas.append(nota)
    promedio_general = round(sum(notas) / len(notas), 2)
    graduada = promedio_general >= 60

    alumnos_dict[nombre] = {
        'name': nombre,
        'age': edad,
        'school': 'UPVM University',
        'promedios': promedios,
        'promedio': promedio_general,
        'graduada': graduada
    }

# Mostrar el diccionario completo
print("Diccionario completo de estudiantes:\n")
for alumno, datos in alumnos_dict.items():
    print(f"{alumno}:")
    for clave, valor in datos.items():
        print(f"  {clave}: {valor}")
    print()
# Crear un diccionario grande con todos los datos del estudiante


















'''  my_dict = dict() 
my_dict = {'name': 'Alice',
            'age': 25,
            'school': 'UPVM University',
            'promedios': [{'Math':80}, {'CompSci':85}, {'Art':90}, {'Biology':75}, {'I.T.':95},{'music':88}, {'Sports':92},
            {'Etics, Morality & laws':80},
                       {'History':85}, {'Geography':90},{'Physics':75},{'Chemistry':95},{'Philosophy':88},{'Psychology':92},
                       {'Sociology':80},{'Economics':85},{'Business Studies':90},
                       {'Accounting':75},{'Marketing':95},{'Finance':88},{'Management Studies':92},{'Law':80},
                       {'Medicine':85},{'Nursing':90},{'Pharmacy':75},{'Dentistry':95},
                       {'Veterinary Science':88},{'Architecture':92},{'Engineering':80},{'Environmental Science':85},
                       {'Astronomy':90},{'Geology':75},{'Anthropology':95},
                       {'Linguistics':88},{'Literature':92},{'Creative Writing':80},{'Drama & Theatre Studies':85},
                       {'Film Studies':90},{'Media Studies':75},
                       {'Production , Engineering musical':95},{'theatre':88},{'Dance':92},
                       {'Photography':80},{'Graphic Design':85},{'Acting':90}],  
                       

} 
notas = [list(nota.values())[0] for nota in my_dict["promedios"]]
promedio = sum(notas) / len(notas)
my_dict["promedio"] = round(promedio, 2) 
my_dict["graduada"] = False
print(f"Diccionario completo del estudiante:")
for clave, valor in my_dict.items():
    print(f"{clave}: {valor}")'''

