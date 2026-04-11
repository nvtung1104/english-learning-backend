<?php
$zip = new ZipArchive();
$file = 'frontend/src/assets/exam/de-thi-anh-thpt-nam-2025-ma-1101-co-loi-giai-chi-tiet-1753951601.docx';
if ($zip->open($file) === TRUE) {
    $xml = $zip->getFromName('word/document.xml');
    $zip->close();
    $text = strip_tags($xml);
    $text = preg_replace('/\s+/', ' ', $text);
    echo substr($text, 0, 8000);
} else {
    echo 'Cannot open';
}
