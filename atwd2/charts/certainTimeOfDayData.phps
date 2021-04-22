<?php

// Set the variables from the POST parameters
$siteID = (int) $_POST['site'];
$year = (int) $_POST['year'];
$time = (string) $_POST['time'];

// Open the XML file for the requested site
$xml = simplexml_load_file('../files/xml/data_' . $siteID . '.xml');

// Initialise the records array with months
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

// Create a new DateTime object for the provided time and retrieve the timestamp for it
$timeToCompare = new DateTime($time);
$timeToCompareTS = $timeToCompare->getTimestamp();

// Calculate the timestamp - 30 minutes and + 30 minutes (to get an average for the hour)
$halfAnHourBelow = $timeToCompareTS - (30 * 60);
$halfAnHourAbove = $timeToCompareTS + (30 * 60);

// Set the recorded flag to false
$recorded = false;

// Loop through each XML rec element
foreach ($xml->rec as $record) {
    // Create a new DateTime object from the rec element's timestamp
    $datetime = new DateTime();
    $datetime->setTimestamp((string) $record['ts']);

    // Create a new DateTime object from the previous DateTime object's hours and minutes
    // This strips the day, month and year information away so we are left with the record's
    // time and no other information
    $recordTime = new DateTime($datetime->format('H:i'));
    $recordTS = $recordTime->getTimestamp();

    // If the record's year matches the requested year, and the record's time is within +- 30 minutes
    // of the requested time
    if ($datetime->format('Y') == $year && ($recordTS >= $halfAnHourBelow && $recordTS <= $halfAnHourAbove)) {
        // Set the recorded flag to true and place the data into the appropriate position
        // in the records array
        $recorded = true;
        $records['rec'][$datetime->format('F')][] = (float) $record['no'];
    }
}

// If we have not recorded any data, return a JSON error message
if (!$recorded) {
    echo json_encode(['error' => 'No data available for the provided parameters']);
    die;
}

// Initialise the averages array
$averages = [];

// Loop through each record in the records array
foreach ($records['rec'] as $key => $value) {
    // Filter out empty values
    $month = array_filter($records['rec'][$key]);
    
    // Calculate the average or set it to null if there is no data
    $average = count($month) != 0 ? array_sum($month)/count($month) : null;

    // Set the average into the averages array
    $averages[$key] = $average;
}

// Format the data into the JSON Data Table format required by Google Charts API
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

// Return the JSON formatted Data Table
echo json_encode($json_data_table);