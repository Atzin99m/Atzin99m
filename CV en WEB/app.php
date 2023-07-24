<?php
require_once('tcpdf/tcpdf.php');

// Cambia 'cv.pdf' al nombre de tu archivo PDF de currículum
$pdfFile = 'cv.pdf';

$tcpdf = new TCPDF();
$tcpdf->setSourceFile($pdfFile);

// Define las páginas y las áreas de texto para extraer
$pages = array(
    1 => array('x' => 10, 'y' => 10, 'w' => 100, 'h' => 50), // Página 1, área 1
    2 => array('x' => 20, 'y' => 20, 'w' => 100, 'h' => 50) // Página 2, área 1
    // Agrega más páginas y áreas si es necesario
);

$sections = array();

foreach ($pages as $pageNumber => $area) {
    $tcpdf->setPage($pageNumber);
    $text = $tcpdf->getTextFromPageArea($area['x'], $area['y'], $area['w'], $area['h']);
    $section = extractSectionFromText($text);

    if ($section) {
        $sections[$pageNumber] = $section;
    }
}

// Devuelve las secciones en formato JSON
header('Content-Type: application/json');
echo json_encode($sections);

function extractSectionFromText($text) {
    $sectionRegexes = array(
        'experience' => '/experience/i',
        'education' => '/education/i',
        'skills' => '/skills/i',
        'contact' => '/contact/i'
        // Agrega más secciones si es necesario
    );

    foreach ($sectionRegexes as $section => $regex) {
        if (preg_match($regex, $text)) {
            return $section;
        }
    }

    return null;
}
?>
