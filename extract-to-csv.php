<?php

// Configuration for performance and time conversion
@date_default_timezone_set("GMT");
ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('auto_detect_line_endings', TRUE);

// Initialise line count, timer and file stream
$lines = 0;
$st = microtime(true);
$file = fopen("air-quality-data-2004-2019.csv","r");

// Create a variable for the headings to be added to each file
$heading = 'siteID,ts,nox,no2,no,pm10,nvpm10,vpm10,nvpm2.5,pm2.5,vpm2.5,co,o3,so2,loc,lat,long';

// Loop through each line
while ($data = fgets($file)) {
    // If the current line is the first line, discard it as it's the headings
    if ($lines == 0) {
        $lines++;
        continue;
    }

    // Get the site id for the current line and create the file name
    // e.g. site 501 -> ./files/data_501.csv
    $siteID = explode(';', $data)[4];
    $siteFile = './files/csv/data_' . $siteID . '.csv';
    
    // If the file doesn't exist for the current site, make it
    if (!file_exists($siteFile)) {
        $file_new = fopen($siteFile, 'a');
        fwrite($file_new, $heading);
        fclose($file_new);
    }

    // Split the current line into pieces
    $read_in_line = explode(';', $data);

    // Convert the DateTime to the Timestamp
    $ts = new DateTime($read_in_line[0]);
    $ts = $ts->getTimestamp();

    // Split the lat/long variable into separate variables
    $latitude = explode(',', $read_in_line[18])[0];
    $longitude = explode(',', $read_in_line[18])[1];

    // Check to see whether we should consider the entry empty
    if ($read_in_line[1] == "" && $read_in_line[11] == "") {
        continue;
    }

    // Initialise array for line components
    $line_to_write = [];

    // Push each element to the line in the correct order
    $line_to_write[] = $siteID;           // Site ID
    $line_to_write[] = $ts;              // Unix Timestamp
    $line_to_write[] = $read_in_line[1]; // NOx
    $line_to_write[] = $read_in_line[2]; // NO2
    $line_to_write[] = $read_in_line[3]; // NO
    $line_to_write[] = $read_in_line[5]; // PM10
    $line_to_write[] = $read_in_line[6]; // NVPM10
    $line_to_write[] = $read_in_line[7]; // VPM10
    $line_to_write[] = $read_in_line[8]; // NVPM2.5
    $line_to_write[] = $read_in_line[9]; // PM2.5
    $line_to_write[] = $read_in_line[10]; // VPM2.5
    $line_to_write[] = $read_in_line[11]; // CO
    $line_to_write[] = $read_in_line[12]; // O3
    $line_to_write[] = $read_in_line[13]; // SO2
    $line_to_write[] = $read_in_line[17]; // Location
    $line_to_write[] = $latitude; // Latitude
    $line_to_write[] = $longitude; // Longitude

    // Open the file resource for the current site in append mode
    $file_site = fopen($siteFile, 'a');

    // Write the new line to the file
    fputs($file_site, PHP_EOL . implode(",", $line_to_write));

    // Close the file
    fclose($file_site);

    // Increment the line count
    $lines++;
}

// Close the original data file
fclose($file);

// Output time taken to split the original data files into the separate site files
echo 'It took ';
echo microtime(true) - $st;
echo ' seconds to split '. $lines .' lines into separate files';