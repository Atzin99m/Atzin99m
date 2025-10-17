document.addEventListener("DOMContentLoaded", () => {
    const tabla = document.querySelector("#tablaTecnicos tbody");
    const form = document.getElementById("formTecnico");
    const inputOrden = document.getElementById("no_orden");
    const tipoFallaInput = document.getElementById("Tipo");

    // --- Cargar registros ---
    async function cargarDatos(filtro = "") {
        try {
            let url = "tecnicos.php";
            if (filtro) url += `?no_orden=${encodeURIComponent(filtro)}`;

            const response = await fetch(url);
            const data = await response.json();

            /// tabla.innerHTML = "";
            data.forEach(row => {
                const tiempoExtra = row.t_extra && row.t_extra.trim() !== "" ? row.t_extra : "--:--";
                const tr = document.createElement("tr");
                tr.innerHTML = `
          <td>${row.id}</td>
          <td contenteditable="true" data-campo="Nombre">${row.Nombre || ""}</td>
          <td contenteditable="true" data-campo="turno">${row.turno || ""}</td>
          <td>${row.no_orden || ""}</td>
          <td>${row.Recepcion || ""}</td>
          <td>${row.recep_maquina || ""}</td>
          <td contenteditable="true" data-campo="Tipo">${row.Tipo || ""}</td>
          <td contenteditable="true" data-campo="Hr_ini">${row.Hr_ini || ""}</td>
          <td contenteditable="true" data-campo="Hr_Fin">${row.Hr_Fin || ""}</td>
          <td contenteditable="true" data-campo="t_extra">${row.t_extra || ""}</td>
          <td>${row.time_total || ""}</td>
          <td contenteditable="true" data-campo="t_realizado">${row.t_realizado || ""}</td>
          <td contenteditable="true" data-campo="observaciones">${row.observaciones || ""}</td>
          <td contenteditable="true" data-campo="Estatus">${row.Estatus || ""}</td>
        `;
                tabla.appendChild(tr);
            });
        } catch (error) {
            console.error("Error al cargar datos:", error);
        }
    }

    // --- Guardar registro ---
    form.addEventListener("submit", async e => {
        e.preventDefault();

        // Solo formatear t_extra si es necesario
        const tExtraInput = form.querySelector(`[name="t_extra"]`);
        if (tExtraInput && tExtraInput.value.length === 5) {
            tExtraInput.value += ":00";
        }

        const datos = new FormData(form);

        try {
            const response = await fetch("tecnicos.php", {
                method: "POST",
                body: datos
            });
            const res = await response.json();
            if (res.success) {
                alert("Orden guardada y clausurada correctamente.");
                form.reset();
                cargarDatos();

                // Recarga la tabla con la hora final incluida
            } else {
                alert(res.mensaje || "Error al guardar.");
            }
        } catch (error) {
            console.error("Error al guardar:", error);
        }
    });

    // --- Editar celdas directamente ---
    tabla.addEventListener("blur", async e => {
        if (e.target.matches("[contenteditable]")) {
            const td = e.target;
            const valor = td.innerText.trim();
            const campo = td.dataset.campo;
            const id = td.parentElement.children[0].innerText;

            try {
                const response = await fetch("tecnicos.php", {
                    method: "PUT",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${id}&campo=${campo}&valor=${encodeURIComponent(valor)}`
                });
                const res = await response.json();
                if (!res.success) alert("Error al actualizar el campo.");
            } catch (error) {
                console.error("Error en actualización:", error);
            }
        }
    }, true);

    // --- Filtro por número de orden ---
    inputOrden.addEventListener("input", e => {
        const valor = e.target.value.trim();
        cargarDatos(valor);
    });

    // --- Obtener tipo de falla desde otra BD ---
    async function obtenerDescripcionDesdeBD(noOrden) {
        try {
            const response = await fetch("connection.php?action=get_all_orders", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ no_orden: noOrden })
            });
            const result = await response.json();

            if (result.success && result.data.length > 0) {
                const descripcionCompleta = result.data[0].descripcion;
                const partes = descripcionCompleta.split(">");
                tipoFallaInput.value = partes[0].trim(); // solo la primera parte
            } else {
                console.warn("No se encontró la orden o no tiene descripción.");
                tipoFallaInput.value = "";
            }
        } catch (error) {
            console.error("Error al obtener la descripción:", error);
        }
    }

    inputOrden.addEventListener("change", () => {
        const noOrden = inputOrden.value.trim();
        if (noOrden) obtenerDescripcionDesdeBD(noOrden);
    });

    // --- Inicialización ---
    cargarDatos();

});