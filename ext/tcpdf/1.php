<?php
require_once('tcpdf.php');  
$fontname = $pdf->addTTFfont('BNazanin.ttf', 'TrueTypeUnicode', '', 32);
var_dump($fontname); 

?>