<?php

namespace Csv;

class CsvReader
{
    use CsvTrait;

    private $firstRowIsKeys = false;

    public function __construct($path = null, $firstRowIsKeys = false, $columnDelimiter = ",", $rowDelimiter = "\n", $enclosure = '"', $skipEmptyRows = true)
    {
        $this->setFirstRowIsKeys($firstRowIsKeys)
            ->setColumnDelimiter($columnDelimiter)
            ->setRowDelimiter($rowDelimiter)
            ->setEnclosure($enclosure)
            ->setSkipEmptyRows($skipEmptyRows);

        if (!is_null($path)) $this->parse($path);
    }

    public function parse($path)
    {
        $this->setPath($path);

        $rows = $this->parseRows();

        if (!empty($rows)) {
            if ($this->getFirstRowIsKeys()) {
                $keys = array_shift($rows);
                $rows = array_values($rows);

                foreach($rows as $key => $column) {
                    $rows[$key] = array_combine($keys, $column);
                }
            } else {
                $keys = array_keys($rows[0]);
            }
        }

        $this->keys = $keys;
        $this->rows = $rows;

        return $this;
    }

    private function parseRows()
    {
        $rows = @file_get_contents($this->getPath());
        if (!$rows) throw new \ErrorException("Cannot read file {$this->getPath()}.");

        $rows = explode($this->getRowDelimiter(), $rows);

        foreach ($rows as $key => $column) {
            if ($this->getSkipEmptyRows() && empty($column)) {
                unset($rows[$key]);
                continue;
            }

            $rows[$key] = str_getcsv($column, $this->getColumnDelimiter(), $this->getEnclosure());
        }

        return array_values($rows);
    }

    private function setPath($path)
    {
        if (!is_file($path)) throw new \ErrorException("Path {$path} is not a file.");
        if (!is_readable($path)) throw new \ErrorException("Path {$path} is not readable.");

        $this->path = realpath($path);

        return $this;
    }


    public function setFirstRowIsKeys($firstRowIsKeys)
    {
        $this->firstRowIsKeys = ($firstRowIsKeys === true);

        return $this;
    }

    public function getFirstRowIsKeys()
    {
        return $this->firstRowIsKeys;
    }

}