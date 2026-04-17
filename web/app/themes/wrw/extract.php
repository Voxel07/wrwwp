<?php
/**
 * Extract rules from DOCX to Markdown.
 *
 * @package wrw
 */

$docx = '/var/www/html/web/app/themes/wrw/WR_Satzung.docx';
if ( ! file_exists( $docx ) ) {
	die( esc_html( 'File not found: ' . $docx ) );
}

$zip = new ZipArchive();
if ( $zip->open( $docx ) === true ) {
	$index = $zip->locateName( 'word/document.xml' );
	if ( false !== $index ) {
		$xml = $zip->getFromIndex( $index );
		$zip->close();

		$dom = new DOMDocument();
		$dom->loadXML( $xml, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING );

		$markdown = "# Wild Rovers Württemberg - Satzung und Regeln\n\n";

		$paragraphs = $dom->getElementsByTagName( 'p' );
		foreach ( $paragraphs as $p ) {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$text = trim( $p->nodeValue );
			if ( ! empty( $text ) ) {
				// If the paragraph is short and uppercase, treat it roughly as a header.
				if ( strlen( $text ) < 60 && strtoupper( $text ) === $text ) {
					$markdown .= '## ' . $text . "\n\n";
				} elseif ( preg_match( '/^[0-9]+\./', $text ) ) {
					$markdown .= '### ' . $text . "\n\n";
				} elseif ( strpos( $text, '§' ) !== false && strlen( $text ) < 60 ) {
					$markdown .= '## ' . $text . "\n\n";
				} else {
					$markdown .= $text . "\n\n";
				}
			}
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		file_put_contents( '/var/www/html/web/app/themes/wrw/regeln.md', $markdown );
		echo "Successfully extracted to regeln.md\n";
	} else {
		echo "Error locating word/document.xml\n";
	}
} else {
	echo "Error opening DOCX\n";
}
