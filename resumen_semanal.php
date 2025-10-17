<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$rows = [];
$exportar = false;

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=cazel_all;charset=utf8", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $maquina = $_POST['Maquina'] ?? '';
        $fecha_inicio = $_POST['fechaInicio'] ?? '';
        $fecha_fin = $_POST['fechaFin'] ?? '';
        $accion = $_POST['accion'] ?? 'ver';

   $sql = "
    SELECT 
        DATE(ot.fh_emision) AS fecha,
        t.turno AS turno,
        t.Nombre AS tecnico,
        t.no_orden AS no_orden,
        ot.maquina AS maquina,
        TIME(t.Hr_ini) AS hora_inicio,
        TIME(t.Hr_Fin) AS hora_fin,
        t.t_extra AS horas_extra,
        IF(t.time_total = 0 OR t.time_total IS NULL, TIMESTAMPDIFF(MINUTE, t.Hr_ini, t.Hr_Fin), t.time_total) AS tiempo_total,
        t.Tipo AS tipo_de_falla,
        ot.descripcion,
        t.t_realizado AS acciones,
        t.observaciones AS comentarios,
        t.Estatus AS Estatus
    FROM orden_trabajo ot
    LEFT JOIN tecnicos t ON ot.id_documento = t.no_orden
    WHERE DATE(ot.fh_emision) BETWEEN :fecha_inicio AND :fecha_fin
      AND (:maquina = '' OR ot.maquina = :maquina)
    ORDER BY ot.fh_emision ASC
";


        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'maquina' => $maquina
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Exportar Excel
        if ($accion === 'exportar') {
            $template = __DIR__ . '/resumen_semanal.xlsx';
            $spreadsheet = IOFactory::load($template);
            $sheet = $spreadsheet->getActiveSheet();

            $rowNum = 3;
            foreach ($rows as $r) {
                $sheet->setCellValue("A{$rowNum}", $r['fecha']);
                $sheet->setCellValue("B{$rowNum}", $r['turno']);
                $sheet->setCellValue("C{$rowNum}", $r['tecnico']);
                $sheet->setCellValue("D{$rowNum}", $r['no_orden']);
                $sheet->setCellValue("E{$rowNum}", $r['maquina']);
                $sheet->setCellValue("F{$rowNum}", $r['hora_inicio']);
                $sheet->setCellValue("G{$rowNum}", $r['hora_fin']);
                $sheet->setCellValue("H{$rowNum}", $r['horas_extra']);
                $sheet->setCellValue("I{$rowNum}", $r['tiempo_total']);
                $sheet->setCellValue("J{$rowNum}", $r['tipo_de_falla']);
                $sheet->setCellValue("K{$rowNum}", $r['descripcion']);
                $sheet->setCellValue("L{$rowNum}", $r['acciones']);
                $sheet->setCellValue("M{$rowNum}", $r['comentarios']);
                $sheet->setCellValue("N{$rowNum}", $r['Estatus']);
                $rowNum++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="resumen_semanal.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error: {$e->getMessage()}</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Resumen Semanal</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body { background: #e9f7ef; }
    h2 { color: #0d6efd; font-weight: bold; text-align: center; margin-bottom: 24px; }

    .login-container {
      max-width: 350px;
      margin: 40px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      padding: 32px 24px;
      border-top: 8px solid #0d6efd;
    }

    label { font-weight: 500; margin-top: 12px; }
    input, select {
      margin-bottom: 10px;
      border-radius: 6px;
      border: 1px solid #0d6efd;
      padding: 8px;
      width: 100%;
    }

    input[type="submit"], button {
      background: #0d6efd;
      color: #fff;
      border: none;
      font-weight: bold;
      border-radius: 6px;
      padding: 10px 0;
      cursor: pointer;
      transition: background 0.2s;
    }
    input[type="submit"]:hover, button:hover { background: #145c32; }
  </style>
</head>

<body class="bg-light">
  <div class="container my-4">
    <div class="text-center mb-3">
      <a href="http://10.0.10.81:8080/IntranetCazel"><img src="img/CAZELlogos-02.png" alt="Logo Cazel" class="img-fluid" style="max-height: 145px; margin-right: 5px;"></a>
      <h2 class="fs-1 text-center mb-3">Reporte Semanal</h2>
    </div>

    <!-- FORMULARIO -->
    <form method="POST" action="" class="row g-3 mb-4">

      <div class="col-md-4 col-12">
        <label for="Maquina" class="form-label">Máquina</label>
        <select name="Maquina" id="Maquina" class="form-select" required>
          <option value="">Selecciona una máquina</option>

          <optgroup label="Nave 1">
            <option value="101B">101B</option>
            <option value="102B">102B</option>
            <option value="103A">103A</option>
            <option value="104B">104B</option>
            <option value="105A">105A</option>
            <option value="106B">106B</option>
            <option value="107C">107C</option>
            <option value="108C">108C</option>
            <option value="109B">109B</option>
            <option value="111B">111B</option>
            <option value="112A">112A</option>
            <option value="113B">113B</option>
            <option value="114B">114B</option>
            <option value="115B">115B</option>
            <option value="116C">116C</option>
            <option value="117B">117B</option>
            <option value="118A">118A</option>
            <option value="121B">121B</option>
            <option value="122A">122A</option>
            <option value="123A">123A</option>
            <option value="124A">124A</option>
            <option value="125A">125A</option>
            <option value="126">126</option>
            <option value="127">127</option>
            <option value="128">128</option>
            <option value="129">129</option>
            <option value="130">130</option>
            <option value="131">131</option>
          </optgroup>

          <optgroup label="Nave 2">
            <option value="202B">202B</option>
            <option value="206">206</option>
            <option value="207">207</option>
            <option value="208A">208A</option>
            <option value="209">209</option>
            <option value="210">210</option>
            <option value="211">211</option>
            <option value="212A">212A</option>
            <option value="214">214</option>
            <option value="215A">215A</option>
            <option value="217A">217A</option>
            <option value="218A">218A</option>
            <option value="219A">219A</option>
          </optgroup>

          <optgroup label="Nave 3">
            <option value="301">301</option>
            <option value="302A">302A</option>
            <option value="303">303</option>
            <option value="304A">304A</option>
            <option value="305A">305A</option>
            <option value="306A">306A</option>
            <option value="307A">307A</option>
            <option value="308">308</option>
            <option value="309A">309A</option>
            <option value="310B">310B</option>
            <option value="311A">311A</option>
            <option value="313B">313B</option>
            <option value="314A">314A</option>
            <option value="315">315</option>
            <option value="316A">316A</option>
            <option value="325A">325A</option>
          </optgroup>
        </select>
      </div>

      <div class="col-md-4 col-12">
        <label class="form-label">Rango de fechas</label>
        <div class="d-flex">
          <input type="date" name="fechaInicio" id="fechaInicio" class="form-control me-2" required>
          <input type="date" name="fechaFin" id="fechaFin" class="form-control" required>
        </div>
      </div>

      <div class="col-12 text-center">
        <button type="submit" name="accion" value="ver" class="btn btn-secondary mt-3">Ver resultados</button>
        <button type="submit" name="accion" value="exportar" class="btn btn-primary mt-3">Exportar Excel</button>
      </div>
    </form>

    <!-- RESULTADOS -->
    <?php if (!empty($rows)): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-sm">
          <thead class="table-dark text-center">
            <tr>
              <th>Fecha</th>
              <th>Técnico</th>
              <th>No. Orden</th>
              <th>Máquina</th>
              <th>Inicio</th>
              <th>Fin</th>
              <th>Horas Extra</th>
              <th>Total</th>
              <th>Mantenimiento</th>
              <th>Descripción</th>
              <th>Acciones</th>
              <th>Comentarios</th>
              <th>Estatus</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['fecha']) ?></td>
                <td><?= htmlspecialchars($r['tecnico']) ?></td>
                <td><?= htmlspecialchars($r['no_orden']) ?></td>
                <td><?= htmlspecialchars($r['maquina']) ?></td>
                <td><?= htmlspecialchars($r['hora_inicio']) ?></td>
                <td><?= htmlspecialchars($r['hora_fin']) ?></td>
                <td><?= htmlspecialchars($r['horas_extra']) ?></td>
                <td><?= htmlspecialchars($r['tiempo_total']) ?></td>
                <td><?= htmlspecialchars($r['tipo_de_falla']) ?></td>
                <td><?= htmlspecialchars($r['descripcion']) ?></td>
                <td><?= htmlspecialchars($r['acciones']) ?></td>
                <td><?= htmlspecialchars($r['comentarios']) ?></td>
                <td><?= htmlspecialchars($r['Estatus']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
      <div class="alert alert-warning text-center">No hay datos para los filtros seleccionados.</div>
    <?php endif; ?>
  </div>

  <script src="main.js"></script>
</body>
</html>
