<?php

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('auto_detect_line_endings', TRUE);

foreach (new DirectoryIterator('./files/csv') as $file) {
    if ($file == '.' || $file == '..') {
        continue;
    }

    if ($file == 'data_481.csv') {
        $xml_file = fopen('./files/xml/data_481.xml', 'w');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<station id="481" ';
        $xml .= 'name="CREATE Centre Roof" ';
        $xml .= 'geocode="51.447213417,-2.62247405516">';
        $xml .= PHP_EOL . '</station>';
        fwrite($xml_file, $xml);
        fclose($xml_file);
        continue;
    }

    $file_stream = fopen('./files/csv/' . $file, 'r');
    
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
            $xml .= 'name="' . urlencode($read_in_line[14]) . '" ';
            $xml .= 'geocode="' . $read_in_line[15] . "," . str_replace("\n", '', $read_in_line[16]) . '">';
        }
        
        if ($read_in_line[2] == '') {
            $lines++;
            continue;
        }

        $xml .= PHP_EOL . '<rec ';
        
        if ($read_in_line[1] != '') {
            $xml .= 'ts="' . $read_in_line[1] .  '" ';
        }
        if ($read_in_line[2] != '') {
            $xml .= 'nox="' . $read_in_line[2] . '" ';
        }
        if ($read_in_line[4] != '') {
            $xml .= 'no="' . $read_in_line[4] . '" ';
        }
        if ($read_in_line[3] != '') {
            $xml .= 'no2="' . $read_in_line[3] . '"';
        }
        
        $xml .= '/>';

        $lines++;
    }

    if ($lines == 1) {

        fclose($empty_file);
        continue;
    }
    
    $xml .= PHP_EOL . '</station>';

    $xml_file = fopen('./files/xml/data_' . $siteID . '.xml', 'a');

    fputs($xml_file, $xml);

    fclose($xml_file);

    fclose($file_stream);
}