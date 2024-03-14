<?php
include_once "conexion.php"; 

session_start();
if (!isset($_SESSION["usuario"])) {
    http_response_code(401);
    echo json_encode(array("exito" => false, "mensaje" => "Acceso no permitido"));
    exit();
}

try {
    $sql = "SELECT cod_tipo_solicitud, tipo_solicitud FROM tipo_solicitud";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($opciones as &$opcion) {
        $opcion['tipo_solicitud'] = htmlspecialchars($opcion['tipo_solicitud']);
    }

    echo json_encode($opciones);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("exito" => false, "mensaje" => "Error en la consulta SQL: " . $e->getMessage()));
}
?>
