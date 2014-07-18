<?php

use Csv\Csv;

class CsvTest extends PHPUnit_Framework_TestCase
{
    private $rows =
        [
            ["row 1", "hello", "world"],
            ["row 2", "hello", "world"],
            ["row 3", "hello", "world"],
        ];

    public function testCountable()
    {
        $csv = new Csv($this->rows);

        $this->assertEquals(count($this->rows), count($csv));
    }

    public function testIterator()
    {
        $csv = new Csv($this->rows);

        foreach ($csv as $key => $value) {
            $this->assertArrayHasKey($key, $this->rows);
            $this->assertEquals($this->rows[$key], $value);
        }
    }

    public function testCsvAttributes()
    {
        $csv             = new Csv();
        $columnDelimiter = ",";
        $rowDelimiter    = "\n";
        $enclosure       = "'";

        $csv->setColumnDelimiter($columnDelimiter);
        $csv->setRowDelimiter($rowDelimiter);
        $csv->setEnclosure($enclosure);

        $this->assertEquals($columnDelimiter, $csv->getColumnDelimiter());
        $this->assertEquals($rowDelimiter, $csv->getRowDelimiter());
        $this->assertEquals($enclosure, $csv->getEnclosure());
    }

    public function testGetRows()
    {
        $csv = new Csv($this->rows);

        $this->assertEquals($this->rows, $csv->getRows());
    }

    public function testEmptyRows()
    {
        $rows   = $this->rows;
        $rows[] = [];
        $rows[] = "";
        $rows[] = null;
        $rows[] = ["row 4", "hello", "world"];

        $csv = new Csv();
        $csv->parse($rows);

        $this->assertEquals(count($rows) - 3, count($csv));
    }

    public function testLoadFile()
    {
        $path = realpath(dirname(__FILE__) . "/../data/us-500.csv");
        $csv  = new Csv($path);
        $rows = $csv->getRows();

    }

} 