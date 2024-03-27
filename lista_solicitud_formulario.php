<?php
include_once "conexion.php"; 

session_start();
if (!isset($_SESSION["usuario"])) {
    http_response_code(401);
    echo json_encode(array("exito" => false, "mensaje" => "Acceso no permitido"));
    exit();
}
try {
    $id_usuario = $_SESSION["usuario"];

    $sql = "SELECT rut, nombre_solicitante, telefono, ingresado, agendado, fecha_ingreso 
            FROM solicitudes 
            WHERE usuario_id = :id_usuario"; 
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($solicitudes as &$solicitud) {
        if ($solicitud['agendado'] === true) {
            $solicitud['estado'] = "Agendado";
        } elseif ($solicitud['agendado'] === null) {
            $solicitud['estado'] = "Ingresado";
        }
        
        unset($solicitud['ingresado']);
        unset($solicitud['agendado']);
    }
    $conn = null;
    echo json_encode($solicitudes);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("exito" => false, "mensaje" => "Error en la consulta SQL: " . $e->getMessage()));
}
?>