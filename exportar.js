document.addEventListener("DOMContentLoaded", () => {
    const tabla = document.querySelector("#tablaTecnicos tbody");

    tabla.addEventListener("dblclick", (e) => {
        const fila = e.target.closest("tr");
        if (!fila) return;

        const id = fila.querySelector("td").innerText.trim();
        if (!id) {
            alert("No se encontró el ID en la fila.");
            return;
        }

        console.log("Exportando técnico con ID:", id);

        // redirige al PHP con GET → descarga directa
        window.location.href = "exportar.php?id=" + encodeURIComponent(id);
    });
});









































/*document.addEventListener("DOMContentLoaded", () => {
    const tabla = document.querySelector("#tablaTecnicos tbody");

    tabla.addEventListener("dblclick", (e) => {
        const fila = e.target.closest("tr");
        if (!fila) return;

        const id = fila.querySelector("td").innerText.trim();
        if (!id) {
            alert("No se encontró el ID en la fila.");
            return;
        }

        console.log("Exportando técnico con ID:", id);

        const form = document.createElement("form");
        form.method = "POST";
        form.action = "exportar.php";

        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "id";
        input.value = id;
        form.appendChild(input);

        document.body.appendChild(form);
        form.submit();
    });
});*/
