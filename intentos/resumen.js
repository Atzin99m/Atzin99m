document.addEventListener("DOMContentLoaded", () => {
  const maquinaSelect = document.getElementById("Maquina");
  const registroExtra = document.getElementById("registro-extra");
  const form = document.getElementById("formResumen");
  const tabla = document.querySelector("#tablaResumen tbody");

  // Mostrar/ocultar select de departamento
  maquinaSelect.addEventListener("change", () => {
    registroExtra.style.display = (maquinaSelect.value === "N/A") ? "block" : "none";
  }); 
//captura formulario
  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Aquí puedes realizar la solicitud AJAX para enviar los datos al servidor
    fetch("resumen.php", {
      method: "POST",
      body: JSON.stringify(data),
      headers: {
        "Content-Type": "application/json"
      }
    })
    .then(response => response.json())
    .then(data => {
      tabla.innerHTML = ""; // Limpiar tabla antes de agregar nuevos datos
      if(data.length == 0){
        tabla.innerHTML= 
        '<tr><td colspan="5">No se encontraron resultados</td></tr>';
      return;
      }
      data.forEach(r =>{
        const row = document.createElement("tr");
        row.innerHTML = `
        <td>${r.fecha}</td>
          <td>${r.tecnico}</td>
          <td>${r.no_orden}</td>
          <td>${r.maquina}</td>
          <td>${r.hora_inicio}</td>
          <td>${r.hora_fin}</td>
          <td>${r.horas_extra}</td>
          <td>${r.tiempo_total}</td>
          <td>${r.tipo_mantenimiento}</td>
          <td>${r.descripcion}</td>
          <td>${r.acciones}</td>
          <td>${r.status}</td>
          <td>${r.comentarios}</td>
        `;
        tabla.appendChild(tr);
      })
      // Procesar la respuesta del servidor
      
    })
    .catch(error => {
      console.error("Error:", error);
    });
  });
});