<?php

require "../vendor/autoload.php";

$source      = "../tests/data/us-500.tsv";
$destination = "./result.csv";
if (file_exists($destination)) unlink($destination);

$csv = new \Csv\Csv();
$csv->setRowDelimiter("\r\n");
$csv->setColumnDelimiter("\t");
$csv->parse($source);

$csv->setColumnDelimiter(",");
$csv->save($destination);



