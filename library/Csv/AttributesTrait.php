<?php

namespace Csv;

trait AttributesTrait
{
    private $columnDelimiter = Csv::DEFAULT_COLUMN_DELIMITER;
    private $rowDelimiter = Csv::DEFAULT_ROW_DELIMITER;
    private $enclosure = Csv::DEFAULT_ENCLOSURE;

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


}