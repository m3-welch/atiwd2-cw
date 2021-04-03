<?php
foreach (new DirectoryIterator('../files/xml') as $file) {
    if ($file->getFilename() == '..' || $file->getFilename() == '.' || $file->getFilename() == 'data_481.xml') {
        continue;
    }

    $domxml = new DOMDocument();
    $domxml->load($file->getPathname());

    print $file->getFilename() . ": Validation " . ($domxml->schemaValidate('../task2/air-quality.xsd') ? 'succeeded! ✓' : 'failed! ✗') . PHP_EOL;
}

print PHP_EOL;