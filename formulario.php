<?php
include_once "conexion.php";
include_once "respuesta.php";

$inputJSON = file_get_contents('php://input');
$datos_tabla = json_decode($inputJSON, true);

if ($response["authenticated"]) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            if ($datos_tabla === null) {
                throw new Exception("Error al decodificar los datos JSON: " . json_last_error_msg(), 400);
            }

            $usuario_logeado = $response["usuario"];
            $fecha_actual = new DateTime('now', new DateTimeZone('UTC'));
            $fecha_actual_str = $fecha_actual->format('Y-m-d H:i:s');
            $errores = [];

            foreach ($datos_tabla as $fila) {
                $rut = $fila["rut"];
                $nombre = $fila["nombre"];
                $telefono = $fila["telefono"];
                $preventiva = $fila["preventiva"];

                $sql = "INSERT INTO solicitudes (rut, nombre_solicitante, usuario, fecha_ingreso) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $rut, PDO::PARAM_STR);
                $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
                $stmt->bindParam(3, $usuario_logeado, PDO::PARAM_STR);
                $stmt->bindParam(4, $fecha_actual_str, PDO::PARAM_STR);

                if (!$stmt->execute()) {
                    $errores[] = "Error al insertar registro: " . implode(", ", $stmt->errorInfo());
                }
            }

            if (!empty($errores)) {
                throw new Exception("Hubo errores al insertar algunos registros", 500);
            }

            echo json_encode(array("exito" => true, "mensaje" => "Todos los registros insertados correctamente"));
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("exito" => false, "message" => $e->getMessage()));
        }
    } else {
        http_response_code(405); // Método no permitido
        echo json_encode(array("exito" => false, "mensaje" => "Acceso no permitido"));
    }
} else {
    http_response_code(401); // No autorizado
    echo json_encode(array("exito" => false, "mensaje" => "Acceso no permitido"));
}
?>
