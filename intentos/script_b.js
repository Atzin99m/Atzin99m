document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById('tecnicoForm');
    const tabla = document.querySelector('tbody');
    const botonGuardar = document.querySelector('button[type="submit"]');

    // Función que se ejecuta al cargar la página para obtener los registros
    async function cargarRegistros() {
        try {
            const res = await fetch('tecnicos.php');
            if (!res.ok) {
                throw new Error('Error al obtener los registros.');
            }
            const data = await res.json();
            
            tabla.innerHTML = ''; // Limpiar la tabla antes de rellenarla
            data.forEach(row => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `<td>${orden.id_documento}</td>
            <td>${orden.fh_emision}</td>
            <td>${orden.departamento}</td>
                       <td>${orden.descripcion}</td>
                        <td>${orden.prioridad}</td>
            <td>${orden.maquina}</td>
            <td>${orden.solicitante}</td>
                      <td>${orden.paro === '1' ? 'Sí' : 'No'}</td>
            <td>${orden.Estatus}</td>
                   
                `;
                // Añadir el evento de clic al botón de editar de la fila
                newRow.querySelector('.editar-btn').addEventListener('click', () => editar(row));
                tabla.appendChild(newRow);
            });
        } catch (error) {
            console.error("Fallo:", error);
            alert("Hubo un error al cargar los registros.");
        }
    }

    // Función para rellenar el formulario con los datos de una fila
    function editar(row) {
         document.getElementById('idGenerado').value = orden.id_documento;
        document.getElementById('solicitante').value = orden.solicitante;
        document.getElementById('mopcion').value = orden.departamento;
        document.getElementById('Maquina').value = orden.maquina;
   
        document.getElementById('fecha_emision').value = orden.fh_emision.substring(0, 16);
            document.getElementById('descripcion_necesidad').value = orden.descripcion;
       
        document.getElementById('prioridad').value = orden.prioridad;
        document.getElementById('tipo-servicio').value = orden.tipo;
        orden.paro === '1' ? document.getElementById('paro_si').checked = true : document.getElementById('paro_no').checked = true;

        botonRegistrar.textContent = 'Guardar Cambios';
        botonEnviar.style.display = 'none';
        obtenerNombreAutorizador(orden.no_autorizador);

        // Cambiar el texto del botón al modo de edición
        botonGuardar.textContent = 'Actualizar';
    }

    // Manejar el envío del formulario
    formulario.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formulario);

        try {
            const response = await fetch('tecnicos.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(result.mensaje);
                formulario.reset();
                document.getElementById('id').value = "0"; // Vuelve a modo "nuevo"
                botonGuardar.textContent = 'Guardar';
                cargarRegistros();
            } else {
                alert(`Error: ${result.mensaje}`);
            }

        } catch (error) {
            console.error("Fallo:", error);
            alert("Hubo un error al procesar el formulario.");
        }
    });

    // Cargar los registros al iniciar la página
    cargarRegistros();
});














































/*document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.querySelector("form");
    const tabla = document.querySelector(".tabla-registros");
    const botonRegistrar = document.getElementById('Registrar');
    const botonEnviar = document.getElementById('Enviar');

    
    // --- Funciones de comunicación con PHP ---

    
    // Función para cargar todas las órdenes en la tabla al inicio
    function cargarTodasLasOrdenes() {
        fetch(`connection.php?action=get_all_orders`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    tabla.innerHTML = ''; // Limpiar la tabla
                    data.data.forEach(orden => crearFilaTabla(orden));
                }
            })
            .catch(error => console.error('Error al cargar órdenes:', error));
    }

    // --- Funciones de la interfaz de usuario ---

    function crearFilaTabla(orden) {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${orden.id_documento}</td>
            <td>${orden.fh_emision}</td>
            <td>${orden.departamento}</td>
                       <td>${orden.descripcion}</td>
                        <td>${orden.prioridad}</td>
            <td>${orden.maquina}</td>
            <td>${orden.solicitante}</td>
                      <td>${orden.paro === '1' ? 'Sí' : 'No'}</td>
            <td>${orden.Estatus}</td>
            <td><button class="btn btn-warning btn-sm editar-btn">Modificar</button></td>
        `;
        fila.querySelector('.editar-btn').addEventListener('click', () => {
            cargarDatosEnFormulario(orden);
        });
        tabla.appendChild(fila);
    }
    
    function cargarDatosEnFormulario(orden) {
        ordenActual = orden;
        modoEdicion = true;
        document.getElementById('idGenerado').value = orden.id_documento;
        document.getElementById('solicitante').value = orden.solicitante;
        document.getElementById('mopcion').value = orden.departamento;
        document.getElementById('Maquina').value = orden.maquina;
   
        document.getElementById('fecha_emision').value = orden.fh_emision.substring(0, 16);
            document.getElementById('descripcion_necesidad').value = orden.descripcion;
       
        document.getElementById('prioridad').value = orden.prioridad;
        document.getElementById('tipo-servicio').value = orden.tipo;
        orden.paro === '1' ? document.getElementById('paro_si').checked = true : document.getElementById('paro_no').checked = true;

        botonRegistrar.textContent = 'Guardar Cambios';
        botonEnviar.style.display = 'none';
        obtenerNombreAutorizador(orden.no_autorizador);
    }

    // --- Eventos del Formulario y Botones ---
    
    // Manejar el submit del formulario (registro o actualización)
    formulario.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(formulario);
        formData.append('action', 'register');
        
        fetch("connection.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                formulario.reset();
               // modoEdicion = false;
                botonRegistrar.textContent = 'Registrar';
                botonEnviar.style.display = 'block';
                cargarTodasLasOrdenes(); // Recargar la tabla con los nuevos datos
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(err => {
            console.error("Fallo:", err);
            alert("Hubo un error al procesar la solicitud.");
        });
    });

    // Manejar el envío de la orden por WhatsApp
    //botonEnviar.addEventListener('click', function() {
        // Lógica de WhatsApp aquí...
    //});
    
    // Cargar las órdenes al inicio de la página

   
   */
   
   
   
   
   
   
   
   
   
   
   // Función para asignar los valores de la fila seleccionada a los campos del formulario
                 /* function asignarValores(fila) { 
                           document.getElementById('mord').value = fila.cells[0].textContent;
                           document.getElementById('memis').value = fila.cells[1].textContent;
                           document.getElementById('mdpt').value = fila.cells[2].textContent;
                           document.getElementById('msec').value = fila.cells[3].textContent;
                           document.getElementById('mdesc').value = fila.cells[4].textContent;
                           document.getElementById('mobs').value = fila.cells[5].textContent;
                            document.getElementById('mtip').value = fila.cells[6].textContent;
                           document.getElementById('mpr').value = fila.cells[7].textContent;
                           document.getElementById('mmaq').value = fila.cells[8].textContent;
                            document.getElementById('paro').value = fila.cells[9].textContent;                          
                           document.getElementById('msol').value = fila.cells[10].textContent;                   
                           document.getElementById('mauto').value = fila.cells[11].textContent;
                          /* document.getElementById('mtec').value = fila.cells[9].textContent;*/
                        /*   document.getElementById('mrep').value = fila.cells[12].textContent;
                           document.getElementById('mstat').value = fila.cells[13].textContent;              
                        document.getElementById('mresol').value = fila.cells[14].textContent;

                  // Añadir evento a cada fila de la tabla para que se llame a la función asignarValores
                /*  document.addEventListener('DOMContentLoaded', function() {
                          var filas = document.querySelectorAll('#resultado tbody tr');
                              filas.forEach(function(fila) {
                                    fila.addEventListener('click', function() {
                                    asignarValores(fila);
                              });
                          }); 
                  });
            
                 function enviarFormulario() {
                       document.getElementById('Form_Principal').submit();
                 }
                 
                 
          
                 //funcion para borrar registro
                /* function confirmarAccion() {
                     var confirmacion = confirm("¿Estás seguro de borrar la linea?");
                     document.getElementById('confirmacion').value = confirmacion;
                     document.getElementById('Form_Principal').submit();
                 }*/
                 
               // function Listas_Embarque() {
                   //  var url = 'WConspart1.php?mensaje=Consulta de Numeros de Parte';
                     /*window.open('Wdetentregas.php?mensaje=Registro de Entregas%20desde%20PHP',
                      '_blank', 'width=600,height=600');*/
                  //   window.open(url, '_blank', 'width=950,height=600');  
        