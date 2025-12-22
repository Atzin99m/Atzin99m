 document.addEventListener("DOMContentLoaded", function() {
     const formulario = document.getElementById("tecnicoForm");
     const tabla = document.querySelector("tbody");
     const botonGuardar = document.querySelector("button[type='submit']");

     formulario.addEventListener("submit", async function(e) {
         e.preventDefault();

         const nivel1El = document.getElementById("nivel1");
         const subcatEl = document.getElementById("subcat");
         const detalleEl = document.getElementById("detalle");

         const nivel1 = nivel1El ? (nivel1El.value || "") : "";
         const subcat = subcatEl ? (subcatEl.value || "") : "";
         const detalle = detalleEl ? (detalleEl.value || "") : "";

         const descripcion = [nivel1, subcat, detalle]
             .filter(function(v) { return v && v.trim() !== ""; })
             .join(" > ");

         const formData = new FormData(formulario);
         formData.append("action", "register");
         formData.append("descripcion", descripcion);

         try {
             const response = await fetch("connection.php", {
                 method: "POST",
                 body: formData,
                 headers: { "Accept": "application/json" }
             });

             // Si el servidor devuelve HTML o vacío, esto lanzará error:
             const result = await response.json();

             if (result && result.success) {
                 //alert(result.message + result.data.id_documento);

                 formulario.reset();
                 var idGenEl = document.getElementById("idGenerado");
                 if (idGenEl) idGenEl.value = result.data.id_documento;
                 if (botonGuardar) botonGuardar.textContent = "Guardar";

                 if (tabla) {
                     const fila = document.createElement("tr");
                     fila.innerHTML =
                         "<td>" + result.data.id_documento + "</td>" +
                         "<td>" + result.data.fh_emision + "</td>" +
                         "<td>" + result.data.descripcion + "</td>" +
                         "<td>" + result.data.maquina + "</td>" +
                         "<td>" + result.data.solicitante + "</td>" +
                         "<td>" + result.data.paro + "</td>";
                     tabla.appendChild(fila);
                 }
                 alert("generado una orden de trabajo con ID: " + result.data.id_documento + "en la maquina: " + result.data.maquina);
                 // Redirigir si lo deseas:
                 // window.location.href = "tecnicos.html?orden=" + result.data.id_documento;
             } else {
                 alert("Error: " + (result && result.message ? result.message : "Respuesta inválida del servidor"));
                 console.warn("Respuesta de error:", result);
             }
         } catch (err) {
             console.error("Error procesando la respuesta:", err);
             alert("Hubo un problema con la respuesta del servidor. Revisa la pestaña Network.");
         }
     });
 });