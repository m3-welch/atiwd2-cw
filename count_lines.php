<?php

@date_default_timezone_set("GMT");

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('auto_detect_line_endings', TRUE);

include('./accuracy_benchmark.php');

$csv_files = [];
$xml_files = [];

print "Counting lines in `./files/csv`..." . PHP_EOL;
foreach (new DirectoryIterator('./files/csv') as $file) {
    if ($file->getFilename() == '..' || $file->getFilename() == '.') {
        continue;
    }

    $csv_files[] = $file->getFilename();
}

sort($csv_files);

foreach ($csv_files as $file) {
    $file_stream = fopen('./files/csv/' . $file, 'r');
    
    $lines = 0;
    
    while ($data = fgets($file_stream)) {
        $lines++;
    }
    
    fclose($file_stream);
    
    print "File `./files/csv/" . $file . "` has " . $lines . " lines. ";
    print "Benchmark: " . $test_1b[explode('_', explode('.', $file)[0])[1]] . ' ';
    print ($test_1b[explode('_', explode('.', $file)[0])[1]] == $lines ? '✓' : '✗') . PHP_EOL;
}

print PHP_EOL . "Counting lines in `./files/xml`..." . PHP_EOL;
foreach (new DirectoryIterator('./files/xml') as $file) {
    if ($file->getFilename() == '..' || $file->getFilename() == '.') {
        continue;
    }

    $xml_files[] = $file->getFilename();
}

sort($xml_files);

foreach ($xml_files as $file) {
    $file_stream = fopen('./files/xml/' . $file, 'r');
    
    $lines = 0;
    
    while ($data = fgets($file_stream)) {
        $lines++;
    }
    
    fclose($file_stream);
    
    print "File `./files/xml/" . $file . "` has " . $lines . " lines. ";
    print "Benchmark: " . $test_2b[explode('_', explode('.', $file)[0])[1]] . ' ';
    print ($test_2b[explode('_', explode('.', $file)[0])[1]] == $lines ? '✓' : '✗') . PHP_EOL;
}

print PHP_EOL;