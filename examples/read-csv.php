<?php

require "../vendor/autoload.php";

$path = "../tests/data/us-500.csv";

$csv = new \Csv\Csv();
$csv->setRowDelimiter("\r\n");
$csv->parse($path);

foreach ($csv as $row) {
    print_r($row);
}

