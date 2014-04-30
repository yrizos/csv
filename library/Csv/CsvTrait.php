<?php

namespace Csv;

trait CsvTrait
{

    private $path;
    private $rows = array();
    private $keys = array();
    private $columnDelimiter = ",";
    private $rowDelimiter = "\n";
    private $enclosure = '"';
    private $skipEmptyRows = true;

    public function getPath()
    {
        return $this->path;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function getKeys()
    {
        return $this->keys;
    }

    public function setColumnDelimiter($delimiter)
    {
        $this->columnDelimiter = $delimiter;

        return $this;
    }

    public function getColumnDelimiter()
    {
        return $this->columnDelimiter;
    }

    public function setRowDelimiter($delimiter)
    {
        $this->rowDelimiter = $delimiter;

        return $this;
    }

    public function getRowDelimiter()
    {
        return $this->rowDelimiter;
    }

    public function setEnclosure($enclosure)
    {
        $this->enclosure = is_string($enclosure) && !empty($enclosure) ? trim($enclosure) : false;

        return $this;
    }

    public function getEnclosure()
    {
        return $this->enclosure;
    }

    public function setSkipEmptyRows($skipEmptyRows)
    {
        $this->skipEmptyRows = ($skipEmptyRows === true);

        return $this;
    }

    public function getSkipEmptyRows()
    {
        return $this->skipEmptyRows;
    }

} 