<?php

$siteID = (int) $_POST['site'];
$year = (int) $_POST['year'];
$time = (string) $_POST['time'];

$xml = simplexml_load_file('../files/xml/data_' . $siteID . '.xml');

$records = [
    'rec' => [
        'January' => [],
        'February' => [],
        'March' => [],
        'April' => [],
        'May' => [],
        'June' => [],
        'July' => [],
        'August' => [],
        'September' => [],
        'October' => [],
        'November' => [],
        'December' => [],
    ]
];

$timeToCompare = new DateTime($time);
$timeToCompareTS = $timeToCompare->getTimestamp();

$halfAnHourBelow = $timeToCompareTS - (30 * 60);
$halfAnHourAbove = $timeToCompareTS + (30 * 60);

$recorded = false;

foreach ($xml->rec as $record) {
    $datetime = new DateTime();
    $datetime->setTimestamp((string) $record['ts']);

    $recordTime = new DateTime($datetime->format('H:i'));

    $recordTS = $recordTime->getTimestamp();

    if ($datetime->format('Y') == $year && ($recordTS >= $halfAnHourBelow && $recordTS <= $halfAnHourAbove)) {
        $recorded = true;
        $records['rec'][$datetime->format('F')][] = (float) $record['no'];
    }
}

if (!$recorded) {
    echo json_encode(['error' => 'No data available for the provided parameters']);
    die;
}

$averages = [];

foreach ($records['rec'] as $key => $value) {
    $month = array_filter($records['rec'][$key]);
    
    $average = count($month) != 0 ? array_sum($month)/count($month) : null;

    $averages[$key] = $average;
}

$json_data_table = [
    'cols' => [
        [
            'label' => 'Month',
            'type' => 'string'
        ],
        [
            'label' => 'NO Levels',
            'type' => 'number'
        ]
    ],
    'rows' => [
        [
            'c' => [
                [
                    'v' => 'January'
                ],
                [
                    'v' => $averages['January']
                ]
            ],
        ],
        [
            'c' => [
                [
                    'v' => 'February'
                ],
                [
                    'v' => $averages['February']
                ]
            ],
        ],
        [
            'c' => [
                [
                    'v' => 'March'
                ],
                [
                    'v' => $averages['March']
                ]
            ],
        ],
        [
            'c' => [
                [
                    'v' => 'April'
                ],
                [
                    'v' => $averages['April']
                ]
            ],
        ],
        [
            'c' => [
                [
                    'v' => 'May'
                ],
                [
                    'v' => $averages['May']
                ]
            ],
        ],
        [
            'c' => [
                [
                    'v' => 'June'
                ],
                [
                    'v' => $averages['June']
                ]
            ],
        ],
        [
            'c' => [
                [
                    'v' => 'July'
                ],
                [
                    'v' => $averages['July']
                ]
            ],
        ],
        [
            'c' => [
                [
                    'v' => 'August'
                ],
                [
                    'v' => $averages['August']
                ]
            ],
        ],
        [
            'c' => [
                [
                    'v' => 'September'
                ],
                [
                    'v' => $averages['September']
                ]
            ],
        ],
        [
            'c' => [
                [
                    'v' => 'October'
                ],
                [
                    'v' => $averages['October']
                ]
            ],
        ],
        [
            'c' => [
                [
                    'v' => 'November'
                ],
                [
                    'v' => $averages['November']
                ]
            ],
        ],
        [
            'c' => [
                [
                    'v' => 'December'
                ],
                [
                    'v' => $averages['December']
                ]
            ],
        ]
    ]
];

echo json_encode($json_data_table);