<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dompdf\Dompdf;


// Obtener el contenido base64 de la imagen del logo
$ruta_logo = 'img/Home.png';
$logo_base64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ruta_logo));

// Contenido HTML del PDF
$html = '
<!DOCTYPE html>
<html>
<head>
<title>Ejemplo de PDF con Logo</title>
<style>
    .logo {
        width: 200px; /* Ajusta el tamaño del logo según tus necesidades */
    }
</style>
</head>
<body>
<h1>PDF con Logo</h1>
<img src="' . $logo_base64 . '" class="logo" alt="Logo">
<p>Este es un ejemplo de PDF que incluye un logo.</p>
</body>
</html>
';

// Crear una instancia de Dompdf
$dompdf = new Dompdf();

// Cargar el HTML en Dompdf
$dompdf->loadHtml($html);

// Renderizar el PDF
$dompdf->render();

// Obtener el contenido del PDF
$pdf_content = $dompdf->output();

// Enviar el PDF al navegador
header('Content-Type: application/pdf');
header('Content-Disposition: attachment;filename="ejemplo_pdf_con_logo.pdf"');
echo $pdf_content;
?>