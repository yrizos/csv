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

        $csv = new Csv();
        $csv->setRowDelimiter("\r\n");
        $csv->parse($path);
        $rows = $csv->getRows();

        $this->assertArrayHasKey(1, $rows);
        $this->assertInternalType("array", $rows[1]);
        $this->assertArrayHasKey(0, $rows[1]);
        $this->assertEquals("James", $rows[1][0]);
    }

    public function testUseFirstRowAsKeys()
    {
        $path = realpath(dirname(__FILE__) . "/../data/us-500.csv");

        $csv = new Csv();
        $csv->setRowDelimiter("\r\n");
        $csv->setUseFirstRowAsKeys(true);
        $csv->parse($path);
        $rows = $csv->getRows();
        $rows = array_slice($rows, 0, 4);

        foreach ($rows as $row) {
            $this->assertArrayHasKey('first_name', $row);
            $this->assertArrayHasKey('last_name', $row);
            $this->assertArrayHasKey('company_name', $row);
            $this->assertArrayHasKey('address', $row);
            $this->assertArrayHasKey('city', $row);
        }
    }


    public function testSaveFile()
    {
        $source      = realpath(dirname(__FILE__) . "/../data/us-500.csv");
        $destination = dirname(__FILE__) . "/../data/us-500.tsv";

        if (file_exists($destination)) unlink($destination);

        $csv = new Csv($source, true, "\r\n");
        $csv->setColumnDelimiter("\t");
        $csv->save($destination);

        $this->assertFileExists($destination);

        $tsv = new Csv($destination, true, "\r\n", "\t");
        $this->assertEquals($tsv->getRows(), $csv->getRows());
    }
} 