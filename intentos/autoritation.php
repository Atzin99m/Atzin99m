<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.html");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "mntto_cazel");
$conexion->set_charset("utf8mb4");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// AJAX para consultar orden por número y devolver JSON
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    $result = $conexion->query("SELECT * FROM orden_trabajo WHERE id_documento = $id");
    if ($result && $result->num_rows > 0) {
        $orden = $result->fetch_assoc();
        echo json_encode(["success" => true, "orden" => $orden]);
    } else {
        echo json_encode(["success" => false, "orden" => null]);
    }
    exit();
}

// AJAX para autorizar orden
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["orden_id"])) {
    $ordenId = intval($_POST["orden_id"]);
    $conexion->begin_transaction();
    try {
        $sql = "UPDATE orden_trabajo SET status = 'Autorizado' WHERE id_documento = $ordenId";

$sql2 = "UPDATE orden_trabajo
        SET Estatus = 'Autorizado', autorizador_nombre = '$autorizador'
        WHERE id_documento = $ordenId";

        $conexion->query($sql);
        $conexion->query($sql2);
        if ($conexion->affected_rows > 0) {
            $conexion->commit();
            echo json_encode(["success" => true]);
        } else {
            $conexion->rollback();
            echo json_encode(["success" => false, "error" => "No se actualizó ninguna orden."]);
        }
    } catch (Exception $e) {
        $conexion->rollback();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
    exit();
}

$areaUsuario = $_SESSION["area"] ?? '';
$ordenes = $conexion->query("SELECT * FROM orden_trabajo WHERE departamento = '$areaUsuario'");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Autorizar Órdenes - <?php echo $areaUsuario ? $areaUsuario : 'Jefe de Área'; ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css"><script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    input.autoriza-input { width: 100%; padding: 4px; font-size: 14px; }
    @media (max-width: 600px) {
      .container { padding: 0 !important; }
      table th, table td { font-size: 0.9rem; padding: 4px; }
      .btn { font-size: 0.95rem; }
    }
  </style>
</head>
<body class="bg-light">
  <div class="container my-4">
    <div class="row">
      <div class="col-12">
        
      </div>
      <div class="col-12 col-md-8 mx-auto">
        <form id="form-consulta" class="mb-4 card card-body shadow-sm">
          <label for="ordenId" class="form-label">Consultar orden por número:</label>
          <div class="input-group mb-2">
            <input type="number" id="ordenId" name="ordenId" class="form-control" required>
            <button type="button" id="btnConsultar" class="btn btn-primary">Consultar</button>
          </div>
        </form>
        <div id="resultadoOrden" class="alert alert-warning" style="display:none;"></div>
        <div id="tabla-autorizacion" style="display:none;" class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
              <tr>
                <th>#Orden</th>
                <th>Solicitante</th>
                <th>Departamento</th>
                <th>Máquina</th>
                <th>Sección</th>
                <th>Descripción</th>
                <th>Observaciones</th>
                <th>Prioridad</th>
                <th>Tipo</th>
                <th>Paro</th>
                <th>Autorizar</th>
              </tr>
            </thead>
            <tbody id="tbody-autorizacion"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script>
    let ordenConsultadaId = null;
    document.getElementById('btnConsultar').addEventListener('click', function() {
      const ordenId = document.getElementById('ordenId').value;
      if (!ordenId) return;
      fetch(window.location.pathname + '?id=' + ordenId)
        .then(res => res.json())
        .then(data => {
          if (data.success && data.orden) {
            ordenConsultadaId = data.orden.id_documento;
            document.getElementById('resultadoOrden').style.display = 'none';
            document.getElementById('tabla-autorizacion').style.display = 'block';
            const row = data.orden;
            document.getElementById('tbody-autorizacion').innerHTML = `
              <tr>
                <td>${row.id_documento}</td>
                <td>${row.solicitante}</td>
                <td>${row.departamento}</td>
                <td>${row.maquina}</td>
                <td>${row.seccion}</td>
                <td>${row.descripcion}</td>
                <td>${row.observaciones}</td>
                <td>${row.prioridad}</td>
                <td>${row.tipo}</td>
                <td>${row.paro ? 'Sí' : 'No'}</td>
                <td>
                  ${row.status === 'Pendiente' ? `
                    <button class="btn btn-success btn-sm btn-autorizar" data-id="${row.id_documento}">Autorizar</button>
                  ` : '<span class="badge bg-success">Autorizado</span>'}
                </td>
              </tr>
            `;
          } else {
            ordenConsultadaId = null;
            document.getElementById('tabla-autorizacion').style.display = 'none';
            document.getElementById('resultadoOrden').style.display = 'block';
            document.getElementById('resultadoOrden').innerHTML = '<p>No se encontró la orden.</p>';
          }
        });
    });

    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('btn-autorizar')) {
        if (!ordenConsultadaId) return;
        fetch('autoritation.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ orden_id: ordenConsultadaId })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            e.target.style.display = 'none';
            const celda = e.target.parentElement;
            celda.innerHTML = '<span class="badge bg-success">Autorizado</span>';
            // Opcional: puedes actualizar el status en la fila si tienes una columna de status
          } else {
            alert('Error al guardar autorización: ' + data.error);
          }
        });
      }
    });
  </script>
</body>
</html>