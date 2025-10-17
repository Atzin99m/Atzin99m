document.addEventListener("DOMContentLoaded", function() {
    const formulario = document.getElementById("tecnicoForm");
    const tabla = document.querySelector("tbody");
    const botonGuardar = document.querySelector("button[type='submit']");

    formulario.addEventListener("submit", async function(e) {
        e.preventDefault();

        // 🟢 Tomar valores de los selects dinámicos

        const nivel1 = document.getElementById("nivel1") ? document.getElementById("nivel1").value : "";
        const subcat = document.getElementById("subcat") ? document.getElementById("subcat").value : "";
        const detalle = document.getElementById("detalle") ? document.getElementById("detalle").value : "";
        // 🟢 Construye descripción combinada
        const descripcion = [nivel1, subcat, detalle]
            .filter(v => v && v.trim() !== "")
            .join(" > ");

        const formData = new FormData(formulario);
        formData.append("action", "register");

        // 🟢 Agrega la descripción generada al FormData
        formData.append("descripcion", descripcion);

        const response = await fetch("connection.php", {
            method: "POST",
            body: formData
        });

        const text = await response.text();
        console.log("Respuesta cruda del servidor:", text);

        let result;
        try {
            result = JSON.parse(text);
        } catch (e) {
            alert("El servidor no devolvió JSON válido. Revisa consola.");
            return;
        }

        if (result.success) {
            alert(result.message);
            //  formulario.reset();
            document.getElementById("idGenerado").value = result.data.id_documento;
            botonGuardar.textContent = "Guardar";

            // 🔹 Se conservan registros anteriores
            const fila = document.createElement("tr");
            fila.innerHTML = `
        <td>${result.data.id_documento}</td>
        <td>${result.data.fh_emision}</td>
        <td>${result.data.descripcion}</td>
        <td>${result.data.maquina}</td>
        <td>${result.data.solicitante}</td>
        <td>${result.data.paro}</td>
      `;
            tabla.appendChild(fila);

            // 🔗 Redirigir a otra página con el número de orden en la URL
            window.location.href = `tecnicos.html?orden=${result.data.id_documento}`;

        } else {
            alert("Error: " + result.message);
        }
    });
});