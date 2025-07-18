<?php
// backup.php
// Incluir la configuración de la base de datos
require 'db.php';

// Nombre del archivo de backup
$backup_file = $dbname . '_' . date("Y-m-d-H-i-s") . '.sql';

// Contenido del backup
$sql_content = "";

// Obtener todas las tablas
$tables = array();
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

// Recorrer cada tabla
foreach ($tables as $table) {
    // Obtener la estructura de la tabla
    $result = $conn->query("SHOW CREATE TABLE $table");
    $row = $result->fetch_row();
    $sql_content .= "\n\n" . $row[1] . ";\n\n";

    // Obtener los datos de la tabla
    $result = $conn->query("SELECT * FROM $table");
    $num_fields = $result->field_count;

    // Formatear los datos como sentencias INSERT
    while ($row = $result->fetch_row()) {
        $sql_content .= "INSERT INTO $table VALUES(";
        for ($j = 0; $j < $num_fields; $j++) {
            $row[$j] = $conn->real_escape_string($row[$j]);
            if (isset($row[$j])) {
                $sql_content .= '"' . $row[$j] . '"';
            } else {
                $sql_content .= 'NULL';
            }
            if ($j < ($num_fields - 1)) {
                $sql_content .= ',';
            }
        }
        $sql_content .= ");\n";
    }
    $sql_content .= "\n";
}

// Cerrar la conexión
$conn->close();

// Configurar cabeceras para forzar la descarga
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($backup_file) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($sql_content));

// Escribir el contenido y finalizar
echo $sql_content;
exit;
?>
