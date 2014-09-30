<?php

require "../vendor/autoload.php";

$path = "../tests/data/us-500.tsv";
$csv = new \Csv\Csv($path, true, "\r\n", "\t");

foreach ($csv as $row) {
    print_r($row);
}

