<?php

require "../vendor/autoload.php";

$path = "../tests/data/us-500.csv";
$csv = new \Csv\Csv($path, true, "\r\n");

foreach ($csv as $row) {
    print_r($row);
}

