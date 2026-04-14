<?php
$docx = '/var/www/html/web/app/themes/wrw/WR_Satzung.docx';
if (!file_exists($docx)) {
    die("File not found: " . $docx);
}

$zip = new ZipArchive;
if ($zip->open($docx) === TRUE) {
    if (($index = $zip->locateName('word/document.xml')) !== false) {
        $xml = $zip->getFromIndex($index);
        $zip->close();
        
        $dom = new DOMDocument();
        $dom->loadXML($xml, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
        
        $markdown = "# Wild Rovers Württemberg - Satzung und Regeln\n\n";
        
        $paragraphs = $dom->getElementsByTagName('p');
        foreach ($paragraphs as $p) {
            $text = trim($p->nodeValue);
            if (!empty($text)) {
                // If the paragraph is short and uppercase, treat it roughly as a header
                if (strlen($text) < 60 && strtoupper($text) === $text) {
                    $markdown .= "## " . $text . "\n\n";
                } else if (preg_match('/^[0-9]+\./', $text)) {
                    $markdown .= "### " . $text . "\n\n";
                } else if (strpos($text, '§') !== false && strlen($text) < 60) {
                    $markdown .= "## " . $text . "\n\n";
                } else {
                    $markdown .= $text . "\n\n";
                }
            }
        }
        
        file_put_contents('/var/www/html/web/app/themes/wrw/regeln.md', $markdown);
        echo "Successfully extracted to regeln.md\n";
    } else {
        echo "Error locating word/document.xml\n";
    }
} else {
    echo "Error opening DOCX\n";
}
