<?php

// Configuration for performance
ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('auto_detect_line_endings', TRUE);

// Loop through each file in the files/csv directory (the files generated)
// by Task 1
foreach (new DirectoryIterator('./files/csv') as $file) {
    // Skip current and previous directories
    if ($file == '.' || $file == '..') {
        continue;
    }

    // If the file is for the empty site (481), create the XML file for it
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

    // Open the file in read mode
    $file_stream = fopen('./files/csv/' . $file, 'r');
    
    // Initialise the line count to 0
    $lines = 0;

    // Create the xml version tag
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    
    // Loop through each line in the generated CSV file
    while ($data = fgets($file_stream)) {
        // Skip the first line (headings)
        if ($lines == 0) {
            $lines++;
            continue;
        }

        // Split the line into the components
        $read_in_line = explode(',', $data);

        // Get the site ID
        $siteID = $read_in_line[0];

        // If the line is the second line create the station tag with the
        // relevant data
        if ($lines == 1) {
            $xml .= '<station id="' . $siteID . '" ';
            $xml .= 'name="' . urlencode($read_in_line[14]) . '" ';
            $xml .= 'geocode="' . $read_in_line[15] . "," . str_replace("\n", '', $read_in_line[16]) . '">';
        }
        
        // If the NOx reading is empty, skip the current line
        if ($read_in_line[2] == '') {
            $lines++;
            continue;
        }

        // Create the start of the rec tag
        $xml .= PHP_EOL . '<rec ';
        
        // If the timestamp is not empty, write the timestamp value
        if ($read_in_line[1] != '') {
            $xml .= 'ts="' . $read_in_line[1] .  '" ';
        }
        // If the NOx is not empty, write the NOx value
        if ($read_in_line[2] != '') {
            $xml .= 'nox="' . $read_in_line[2] . '" ';
        }
        // If the NO is not empty, write the NO value
        if ($read_in_line[4] != '') {
            $xml .= 'no="' . $read_in_line[4] . '" ';
        }
        // If the NO2 is not empty, write the NO2 value
        if ($read_in_line[3] != '') {
            $xml .= 'no2="' . $read_in_line[3] . '"';
        }

        // End the rec tag        
        $xml .= '/>';

        // Increment the line count
        $lines++;
    }

    // If the file only has 1 line (site 481), close the file
    if ($lines == 1) {
        fclose($empty_file);
        continue;
    }
    
    // Close the station tag
    $xml .= PHP_EOL . '</station>';

    // Open the new XML file
    $xml_file = fopen('./files/xml/data_' . $siteID . '.xml', 'a');

    // Write the contents of the XML file
    fputs($xml_file, $xml);

    // Close the new XML file
    fclose($xml_file);

    // Close the original CSV file
    fclose($file_stream);
}

echo "XML Generation Complete";