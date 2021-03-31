<?php

include_once('sites_list.php');

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

foreach ($sites as $siteID => $siteName) {

    $xml = simplexml_load_file('../files/xml/data_' . $siteID . '.xml');

    $geocode = explode(',', $xml['geocode']);

    foreach ($records as $month => $array)
    $records[$month]['stations'][$siteID] = [
        'lat' => $geocode[0],
        'long' => $geocode[1],
        'name' => $siteName,
        'data' => []
    ];
}

foreach ($sites as $siteID => $siteName) {
    $xml = simplexml_load_file('../files/xml/data_' . $siteID . '.xml');

    foreach ($xml->rec as $record) {
        $datetime = new DateTime();
        $datetime->setTimestamp((string) $record['ts']);

        $records[$datetime->format('M')]['stations'][$siteID]['data']['nox'][] = (float) $record['nox'] ?? null;
        $records[$datetime->format('M')]['stations'][$siteID]['data']['no'][] = (float) $record['no'] ?? null;
        $records[$datetime->format('M')]['stations'][$siteID]['data']['no2'][] = (float) $record['no2'] ?? null;
    }
}

$average = [];

foreach ($records as $month => $value) {
    $filtered_array = [];

    foreach($value['stations'] as $siteID => $data) {
        $geocode = explode(',', $xml['geocode']);
        $average[$month][$siteID]['lat'] = $geocode[0];
        $average[$month][$siteID]['long'] = $geocode[1];
        $average[$month][$siteID]['name'] = $sites[$siteID];                    

        $filtered_array['nox'] = array_filter($data['data']['nox']);
        $average[$month][$siteID]['data']['nox'] = count($filtered_array['nox']) != 0 ? array_sum($filtered_array['nox']) / count($filtered_array['nox']) : null;

        $filtered_array['no'] = array_filter($data['data']['no']);
        $average[$month][$siteID]['data']['no'] = count($filtered_array['no']) != 0 ? array_sum($filtered_array['no']) / count($filtered_array['no']) : null;

        $filtered_array['no2'] = array_filter($data['data']['no2']);
        $average[$month][$siteID]['data']['no2'] = count($filtered_array['no2']) != 0 ? array_sum($filtered_array['no2']) / count($filtered_array['no2']) : null;
    }


}

echo json_encode($average);
die;