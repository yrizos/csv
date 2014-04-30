<?php

require "../library/Csv/CsvTrait.php";
require "../library/Csv/CsvReader.php";
require "../library/Csv/CsvWriter.php";

$path      = "./csv/us-500.csv";
$csvReader = new \Csv\CsvReader($path, true, ",", "\r", '"', true);
$rows      = $csvReader->getRows();

$path      = "./csv/us-500.tsv";

$keys = array(
    "key1",
    "key2",
    "key3",
    "key4",
    "key5",
    "key6",
    "key7",
    "key8",
    "key9",
    "key10",
    "key11",
    "key12",
);

$csvWriter = new \Csv\CsvWriter($rows, $keys, "\t", "\n");
$tsv       = $csvWriter->getCsv();
file_put_contents($path, $tsv);


$tsvReader = new \Csv\CsvReader($path, false, "\t", "\n");
$rows      = $tsvReader->getRows();

var_dump($rows);
