<?php

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('auto_detect_line_endings', TRUE);

foreach (new DirectoryIterator('./files') as $file) {
    $file_stream = fopen('./files/' . $file, 'r');
    
    $lines = 0;

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    
    while ($data = fgets($file_stream)) {
        if ($lines == 0) {
            $lines++;
            continue;
        }

        $read_in_line = explode(',', $data);

        $siteID = $read_in_line[0];

        if ($lines == 1) {
            $xml .= '<station id="' . $siteID . '" ';
            $xml .= 'name="' . $read_in_line[14] . '" ';
            $xml .= 'geocode="' . $read_in_line[15] . "," . str_replace("\n", '', $read_in_line[16]) . '">';
        }
        $lines++;
    }

    if ($lines == 1) {
        continue;
    }
    
    $xml .= PHP_EOL . '</station>';

    $xml_file = fopen('./files/data_' . $siteID . '.xml', 'a');

    fputs($xml_file, $xml);

    fclose($xml_file);

    fclose($file_stream);
}