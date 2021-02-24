<?php

@date_default_timezone_set("GMT");

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('auto_detect_line_endings', TRUE);

foreach (new DirectoryIterator('./files') as $file) {
    $file_stream = fopen('./files/' . $file, 'r');
    
    $lines = 0;
    
    while ($data = fgets($file_stream)) {
        $lines++;
    }
    
    fclose($file_stream);
    
    print "File ./files/" . $file . " has " . $lines . " lines.". PHP_EOL;
}