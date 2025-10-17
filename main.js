document.addEventListener("DOMContentLoaded", () => {
  const maquinaSelect = document.getElementById("Maquina");
  const registroExtra = document.getElementById("registro-extra");

  maquinaSelect.addEventListener("change", () => {
    registroExtra.style.display = (maquinaSelect.value === "N/A") ? "block" : "none";
  });
});
