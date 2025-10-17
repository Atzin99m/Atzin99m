document.addEventListener("DOMContentLoaded", function() {
    const tabla = document.querySelector("#tablaTecnicos tbody");
    const form = document.getElementById("formTecnico");
    const inputOrden = document.getElementById("no_orden");

    function cargarDatos(filtro = "") {
        let url = "tecnicos.php";
        if (filtro) {
            url += `?no_orden=${encodeURIComponent(filtro)}`;
        }

        fetch(url)
            .then(r => r.json())
            .then(data => {
                tabla.innerHTML = "";
                data.forEach(row => {
                    let tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${row.id}</td>
                        <td contenteditable="true" data-campo="Nombre">${row.Nombre}</td>
                        <td contenteditable="true" data-campo="turno">${row.turno}</td>
                        <td>${row.no_orden}</td>
                        <td>${row.Recepcion}</td>
                        <td>${row.recep_maquina}</td>
                        <td contenteditable="true" data-campo="Tipo">${row.Tipo}</td>
                        <td contenteditable="true" data-campo="Hr_ini">${row.Hr_ini}</td>
                        <td contenteditable="true" data-campo="Hr_Fin">${row.Hr_Fin}</td>
                        <td contenteditable="true" data-campo="t_extra">${row.t_extra}</td>
                        <td>${row.time_total}</td>
                        <td contenteditable="true" data-campo="t_realizado">${row.t_realizado}</td>
                        <td contenteditable="true" data-campo="observaciones">${row.observaciones}</td>
                        <td contenteditable="true" data-campo="Estatus">${row.Estatus}</td>
                    `;
                    tabla.appendChild(tr);
                });
            });
    }

    form.addEventListener("submit", e => {
        e.preventDefault();

        ["", "Hr_Fin", "t_extra"].forEach(name => {
            const input = form.querySelector(`[name="${name}"]`);
            if (input && input.value.length === 5) {
                input.value += ":00";
            }
        });

        const datos = new FormData(form);
        fetch("tecnicos.php", {
                method: "POST",
                body: datos
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    form.reset();
                    cargarDatos();
                } else {
                    alert(res.mensaje || "Error al guardar");
                }
            });
    });

    tabla.addEventListener("blur", e => {
        if (e.target.matches("[contenteditable]")) {
            const td = e.target;
            const valor = td.innerText.trim();
            const campo = td.dataset.campo;
            const id = td.parentElement.children[0].innerText;

            fetch("tecnicos.php", {
                    method: "PUT",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${id}&campo=${campo}&valor=${encodeURIComponent(valor)}`
                })
                .then(r => r.json())
                .then(res => {
                    if (!res.success) {
                        alert("Error al actualizar");
                    }
                });
        }
    }, true);

    inputOrden.addEventListener("input", e => {
        const valor = e.target.value.trim();
        if (valor === "") {
            cargarDatos();
        } else {
            cargarDatos(valor);
        }
    });

    cargarDatos();
});