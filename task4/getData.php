<?php

// Set parameters for performance
ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('auto_detect_line_endings', TRUE);

// Include the site list to map site IDs to site names
include_once('sites_list.php');

// Initialise the records array with months
$records = [
    'Jan' => [
        'stations' => []
    ],
    'Feb' => [
        'stations' => []
    ],
    'Mar' => [
        'stations' => []
    ],
    'Apr' => [
        'stations' => []
    ],
    'May' => [
        'stations' => []
    ],
    'Jun' => [
        'stations' => []
    ],
    'Jul' => [
        'stations' => []
    ],
    'Aug' => [
        'stations' => []
    ],
    'Sep' => [
        'stations' => []
    ],
    'Oct' => [
        'stations' => []
    ],
    'Nov' => [
        'stations' => []
    ],
    'Dec' => [
        'stations' => []
    ],
];

// Loop through all sites
foreach ($sites as $siteID => $siteName) {
    // Open the XML file for the current site
    $xml = simplexml_load_file('../files/xml/data_' . $siteID . '.xml');

    // Split the geocode into 2 parts (lat and long)
    $geocode = explode(',', $xml['geocode']);

    // Loop through all the months
    foreach ($records as $month => $array) {
        // Put the station's data in the appropriate place in the records array
        $records[$month]['stations'][$siteID] = [
            'lat' => $geocode[0],
            'long' => $geocode[1],
            'name' => $siteName,
            'data' => []
        ];
    }
}

// Loop through all the sites
foreach ($sites as $siteID => $siteName) {
    // Open the XML file
    $xml = simplexml_load_file('../files/xml/data_' . $siteID . '.xml');

    // Loop through all record XML elements
    foreach ($xml->rec as $record) {
        // Create a new DateTime object from the record's timestamp
        $datetime = new DateTime();
        $datetime->setTimestamp((string) $record['ts']);

        // Place the data in the appropriate position in the records array
        $records[$datetime->format('M')]['stations'][$siteID]['data']['nox'][] = (float) $record['nox'] ?? null;
        $records[$datetime->format('M')]['stations'][$siteID]['data']['no'][] = (float) $record['no'] ?? null;
        $records[$datetime->format('M')]['stations'][$siteID]['data']['no2'][] = (float) $record['no2'] ?? null;
    }
}

// Initialise the average array
$average = [];

// Loop through the records array
foreach ($records as $month => $value) {
    // Initialise the filtered_array
    $filtered_array = [];

    // Loop through each station in the records array
    foreach($value['stations'] as $siteID => $data) {
        // Put the site info in the appropriate position in the average array
        $average[$month][$siteID]['lat'] = $records[$month]['stations'][$siteID]['lat'];
        $average[$month][$siteID]['long'] = $records[$month]['stations'][$siteID]['long'];
        $average[$month][$siteID]['name'] = $sites[$siteID];                    

        // Average the data and place it in the appropriate position in the average array
        $filtered_array['nox'] = array_filter($data['data']['nox']);
        $average[$month][$siteID]['data']['nox'] = count($filtered_array['nox']) != 0 ? array_sum($filtered_array['nox']) / count($filtered_array['nox']) : null;

        $filtered_array['no'] = array_filter($data['data']['no']);
        $average[$month][$siteID]['data']['no'] = count($filtered_array['no']) != 0 ? array_sum($filtered_array['no']) / count($filtered_array['no']) : null;

        $filtered_array['no2'] = array_filter($data['data']['no2']);
        $average[$month][$siteID]['data']['no2'] = count($filtered_array['no2']) != 0 ? array_sum($filtered_array['no2']) / count($filtered_array['no2']) : null;
    }
}

// return the JSON formatted average array
echo json_encode($average);
die;