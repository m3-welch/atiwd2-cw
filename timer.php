<?php
#timer script for fgets() and fgetscsv()
$lines = 0;
$st = microtime(true);
$file = fopen("air-quality-data-2004-2019.csv","r");

$file_copy = fopen('air-quality-data-2004-2019_copy.csv', 'w');

while ($data = fgets($file)) {
    fwrite($file_copy, $data);
    $lines++;
}

fclose($file_copy);

fclose($file);

echo '<p>It took ';
echo microtime(true) - $st;
echo ' seconds to get at '. $lines .' lines using fgets().</p>';

$lines = 0;
$st = microtime(true);
$file = fopen("air-quality-data-2004-2019.csv","r");

while ($data = fgetcsv($file)) {
    $lines++;
}

fclose($file);

echo '<p>It took ';
echo microtime(true) - $st;
echo ' seconds to get at '. $lines .' lines using fgetcsv().</p>';
