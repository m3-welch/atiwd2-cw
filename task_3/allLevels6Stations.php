<?php

$sites = explode(',', $_POST['sites']);
$date = $_POST['date'];

$records = [
    'rec' => []
];

$date = new DateTime($date);

$json_data_table = [];

$recorded = false;

foreach ($sites as $siteID) {

    $xml = simplexml_load_file('../files/xml/data_' . $siteID . '.xml');


    foreach ($xml->rec as $record) {
        $datetime = new DateTime();
        $datetime->setTimestamp((string) $record['ts']);

        if ($date->format('Y-m-d') == $datetime->format('Y-m-d')) {
            $recorded = true;

            $records['rec'][$siteID][$datetime->format('H') . ":00"]['nox'][] = (float) $record['nox'] ?? null;
            $records['rec'][$siteID][$datetime->format('H') . ":00"]['no'][] = (float) $record['no'] ?? null;
            $records['rec'][$siteID][$datetime->format('H') . ":00"]['no2'][] = (float) $record['no2'] ?? null;
        }
    }
}

if (!$recorded) {
    echo json_encode(['error' => 'No data available for the provided parameters']);
    die;
}

foreach ($sites as $siteID) {
    $averages = [];

    foreach ($records['rec'][$siteID] as $key => $value) {
        $nox = array_filter($records['rec'][$siteID][$key]['nox']);
        $no = array_filter($records['rec'][$siteID][$key]['no']);
        $no2 = array_filter($records['rec'][$siteID][$key]['no2']);


        
        $average_nox = count($nox) != 0 ? array_sum($nox)/count($nox) : null;
        $average_no = count($no) != 0 ? array_sum($no)/count($no) : null;
        $average_no2 = count($no2) != 0 ? array_sum($no2)/count($no2) : null;

        $averages[$siteID][$key]['nox'] = $average_nox;
        $averages[$siteID][$key]['no'] = $average_no;
        $averages[$siteID][$key]['no2'] = $average_no2;
    }

    $json_data_table[$siteID] = [
        'cols' => [
            [
                'label' => 'Time',
                'type' => 'string'
            ],
            [
                'label' => 'NOx Levels',
                'type' => 'number'
            ],
            [
                'label' => 'NO Levels',
                'type' => 'number'
            ],
            [
                'label' => 'NO2 Levels',
                'type' => 'number'
            ],
        ],
        'rows' => [
            [
                'c' => [
                    [
                        'v' => '00:00'
                    ],
                    [
                        'v' => $averages[$siteID]['00:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['00:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['00:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '01:00'
                    ],
                    [
                        'v' => $averages[$siteID]['01:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['01:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['01:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '02:00'
                    ],
                    [
                        'v' => $averages[$siteID]['02:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['02:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['02:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '03:00'
                    ],
                    [
                        'v' => $averages[$siteID]['03:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['03:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['03:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '04:00'
                    ],
                    [
                        'v' => $averages[$siteID]['04:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['04:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['04:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '05:00'
                    ],
                    [
                        'v' => $averages[$siteID]['05:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['05:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['05:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '06:00'
                    ],
                    [
                        'v' => $averages[$siteID]['06:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['06:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['06:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '07:00'
                    ],
                    [
                        'v' => $averages[$siteID]['07:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['07:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['07:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '08:00'
                    ],
                    [
                        'v' => $averages[$siteID]['08:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['08:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['08:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '09:00'
                    ],
                    [
                        'v' => $averages[$siteID]['09:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['09:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['09:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '10:00'
                    ],
                    [
                        'v' => $averages[$siteID]['10:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['10:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['10:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '11:00'
                    ],
                    [
                        'v' => $averages[$siteID]['11:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['11:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['11:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '12:00'
                    ],
                    [
                        'v' => $averages[$siteID]['12:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['12:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['12:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '13:00'
                    ],
                    [
                        'v' => $averages[$siteID]['13:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['13:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['13:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '14:00'
                    ],
                    [
                        'v' => $averages[$siteID]['14:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['14:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['14:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '15:00'
                    ],
                    [
                        'v' => $averages[$siteID]['15:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['15:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['15:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '16:00'
                    ],
                    [
                        'v' => $averages[$siteID]['16:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['16:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['16:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '17:00'
                    ],
                    [
                        'v' => $averages[$siteID]['17:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['17:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['17:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '18:00'
                    ],
                    [
                        'v' => $averages[$siteID]['18:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['18:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['18:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '19:00'
                    ],
                    [
                        'v' => $averages[$siteID]['19:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['19:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['19:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '20:00'
                    ],
                    [
                        'v' => $averages[$siteID]['20:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['20:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['20:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '21:00'
                    ],
                    [
                        'v' => $averages[$siteID]['21:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['21:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['21:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '22:00'
                    ],
                    [
                        'v' => $averages[$siteID]['22:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['22:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['22:00']['no2']
                    ],
                ],
            ],
            [
                'c' => [
                    [
                        'v' => '23:00'
                    ],
                    [
                        'v' => $averages[$siteID]['23:00']['nox']
                    ],
                    [
                        'v' => $averages[$siteID]['23:00']['no']
                    ],
                    [
                        'v' => $averages[$siteID]['23:00']['no2']
                    ],
                ],
            ],
        ]
    ];
}


echo json_encode($json_data_table);