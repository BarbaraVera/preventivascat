<?php
include_once "conexion.php";
include_once "respuesta.php";
require_once __DIR__ . '/vendor/autoload.php';
include "lib/barcode.php";

use Dompdf\Dompdf;

//dominios
$dominios = array("http://localhost");

//verifico dominio
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $dominios)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
} else {
    http_response_code(403);
    exit();
}

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

            $letras = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $numeros = '0123456789';

            $caracteres = $letras . $numeros;
            $longitud = 20;

            $cadena = '';
            for ($i = 0; $i < $longitud; $i++) {
                $cadena .= $caracteres[rand(0, strlen($caracteres) - 1)];
            }

            foreach ($datos_tabla as $fila) {
                $rut = $fila["rut"];
                $nombre = $fila["nombre"];
                $telefono = $fila["telefono"];
                $comentario = $fila["comentario"];
                $tipo = $fila["tipo"];

                if (!empty($fila["paquete"])) {
                    $paquete = $fila["paquete"];
                    $stmt = $conn->prepare("INSERT INTO solicitudes (rut, nombre_solicitante, usuario_id, fecha_ingreso, telefono, comentario, paquete_id, tipo_solicitud_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bindParam(1, $rut, PDO::PARAM_STR);
                    $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(3, $usuario_logeado, PDO::PARAM_STR);
                    $stmt->bindParam(4, $fecha_actual_str, PDO::PARAM_STR);
                    $stmt->bindParam(5, $telefono, PDO::PARAM_STR);
                    $stmt->bindParam(6, $comentario, PDO::PARAM_STR);
                    $stmt->bindParam(7, $paquete, PDO::PARAM_STR);
                    $stmt->bindParam(8, $tipo, PDO::PARAM_STR);

                } else {
                    $stmt = $conn->prepare("INSERT INTO solicitudes (rut, nombre_solicitante, usuario_id, fecha_ingreso, telefono, comentario, tipo_solicitud_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bindParam(1, $rut, PDO::PARAM_STR);
                    $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(3, $usuario_logeado, PDO::PARAM_STR);
                    $stmt->bindParam(4, $fecha_actual_str, PDO::PARAM_STR);
                    $stmt->bindParam(5, $telefono, PDO::PARAM_STR);
                    $stmt->bindParam(6, $comentario, PDO::PARAM_STR);
                    $stmt->bindParam(7, $tipo, PDO::PARAM_STR);
                }

                if (!$stmt->execute()) {
                    $errores[] = "Error al insertar registro: " . implode(", ", $stmt->errorInfo());
                }
            }

            if (!empty($errores)) {
                throw new Exception("Hubo errores al insertar algunos registros", 500);
            }

            $stmt2 = $conn->prepare("INSERT INTO qr_solicitudes (cod_encrip, valido) VALUES (?, ?)");
            $stmt2->bindParam(1, $cadena, PDO::PARAM_STR);
            $valido = 'true'; 
            $stmt2->bindParam(2, $valido, PDO::PARAM_STR);

            if (!$stmt2->execute()) {
                $errores[] = "Error al insertar registro en qr_solicitudes: " . implode(", ", $stmt2->errorInfo());
            }

            $dompdf = new Dompdf();

            $template_html = file_get_contents(__DIR__ . '/pdf.html');
            $template_html = str_replace('{titulo}', 'Solicitudes ingresadas', $template_html);

            $ruta_logo = 'img/Home.png';
            $logo_base64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ruta_logo));
            $template_html = str_replace('{logo}', '<img src="' . $logo_base64 . '" class="logo" alt="Logo">', $template_html);

            $tabla_data = '';
            foreach ($datos_tabla as $fila) {
                $tabla_data .= '<tr>
                                    <td>' . $fila["rut"] . '</td>
                                    <td>' . $fila["nombre"] . '</td>
                                    <td>' . $fila["telefono"] . '</td>
                                    <td>' . $fila["comentario"] . '</td>
                                </tr>';
            }

            $template_html = str_replace('{tabla_data}', $tabla_data, $template_html);

            $url = 'http://192.168.1.84:80/qr_preventivas/datosqr.php?id=' . $cadena;
            $generator = new barcode_generator();
            $svg = $generator->render_svg("qr", $url, "");

            $template_html = str_replace('{codigo_qr}', '<img src="data:image/svg+xml;base64,' . base64_encode($svg) . '" class="qr-code" />', $template_html);
            $dompdf->loadHtml($template_html);

            $dompdf->render();

            $pdf_content = $dompdf->output();

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment;filename="reporte_solicitudes.pdf"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

            echo $pdf_content;

            echo json_encode(array("exito" => true, "mensaje" => "Todos los registros insertados correctamente"));
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("exito" => false, "mensaje" => $e->getMessage()));
        }
    } else {
        http_response_code(405); 
        echo json_encode(array("exito" => false, "mensaje" => "Acceso no permitido"));
    }
} else {
    http_response_code(401); 
    echo json_encode(array("exito" => false, "mensaje" => "Acceso no permitido"));
}
?>
