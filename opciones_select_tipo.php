<?php
include_once "conexion.php"; 

session_start();
if (!isset($_SESSION["usuario"])) {
    http_response_code(401);
    echo json_encode(array("exito" => false, "mensaje" => "Acceso no permitido"));
    exit();
}

try {
    $mutual_usuario = '';
    $response["usuario"] = $_SESSION['usuario'];

    $query = "SELECT mutual FROM usuario WHERE usuario = :usuario";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':usuario', $_SESSION['usuario'], PDO::PARAM_STR);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mutual_usuario = $row["mutual"];
    }
    if($mutual_usuario){
        $sql = "SELECT cod_tipo_solicitud, tipo_solicitud FROM tipo_solicitud WHERE mutual = :mutual";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':mutual', $mutual_usuario); 
        $stmt->execute();
        $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($opciones as &$opcion) {
            $opcion['tipo_solicitud'] = htmlspecialchars($opcion['tipo_solicitud']);
        }

        echo json_encode($opciones);
    }else{
        $empresa = true;
        $sql = "SELECT cod_tipo_solicitud, tipo_solicitud FROM tipo_solicitud WHERE empresa = :empresa";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':empresa', $empresa); 
        $stmt->execute();
        $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($opciones as &$opcion) {
            $opcion['tipo_solicitud'] = htmlspecialchars($opcion['tipo_solicitud']);
        }

        echo json_encode($opciones);
    }

    $conn = null;
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("exito" => false, "mensaje" => "Error en la consulta SQL: " . $e->getMessage()));
}
?>
