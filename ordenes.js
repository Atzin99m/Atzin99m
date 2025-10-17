document.querySelector('input[name="no_orden"]').addEventListener('blur', function() {
    const noOrden = this.value;
    if (noOrden) {
        fetch(`find_id.php?no_orden=${noOrden}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('descripcion').value = data.descripcion || 'No encontrada';
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('descripcion').value = 'Error al buscar';
            });
    }
});
