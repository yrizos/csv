<?php

require "../vendor/autoload.php";

$path = "../tests/data/us-500.tsv";

$csv = new \Csv\Csv();
$csv->setRowDelimiter("\r\n");
$csv->setColumnDelimiter("\t");
$csv->parse($path);

foreach ($csv as $row) {
    print_r($row);
}

