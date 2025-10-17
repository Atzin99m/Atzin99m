     document.addEventListener("DOMContentLoaded", () => {
         const noOrdenInput = document.getElementById("no_orden");
         const tipoFallaInput = document.getElementById("Tipo");

         async function obtenerDescripcionDesdeBD(noOrden) {
             try {
                 const response = await fetch("connection.php?action=get_all_orders", {
                     method: "POST",
                     headers: {
                         "Content-Type": "application/json"
                     },
                     body: JSON.stringify({
                         no_orden: noOrden
                     })
                 });

                 const result = await response.json();

                 if (result.success && result.data.length > 0) {
                     const descripcionCompleta = result.data[0].descripcion;
                     const partes = descripcionCompleta.split(">");

                     // ✅ OPCIÓN 1: Solo la primera parte
                     const tipoFalla = partes[0].trim();

                     // ✅ OPCIÓN 2: Las dos primeras partes
                     //  const tipoFalla = partes.slice(0, 2).map(p => p.trim()).join(" > ");

                     tipoFallaInput.value = tipoFalla;
                 } else {
                     console.warn("No se encontró la orden o no tiene descripción.");
                 }
             } catch (error) {
                 console.error("Error al obtener la descripción:", error);
             }
         }

         // Ejecutar cuando el campo no_orden cambie
         noOrdenInput.addEventListener("change", () => {
             const noOrden = noOrdenInput.value;
             if (noOrden) {
                 obtenerDescripcionDesdeBD(noOrden);
             }
         });
     });
     /**function enviarFormulario() {
       const formData = new FormData(document.getElementById('miFormulario'));

       fetch('connection.php', {
         method: 'POST',
         body: formData
       })
       .then(response => response.json())
       .then(data => {
         if (data.id) {
           document.getElementById('idGenerado').value = data.id;
         } else {
           alert('Error al guardar los datos');
         }
       })
       .catch(error => console.error('Error:', error));
     } ;
      <select id="tecnico" name="tecnico" class="form-control"></select>


     fetch('select.php')
       .then(response => response.json())
       .then(data => {
         const select = document.getElementById('tecnico');
         data.forEach(tecnico => {
           const option = document.createElement('option');
           option.value = tecnico.id;
           option.textContent = tecnico.Nombre;
           select.appendChild(option);
         });
       })
       .catch(error => console.error('Error al cargar técnicos:', error));


       </select>
      */