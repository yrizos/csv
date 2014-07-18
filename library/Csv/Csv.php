<?php

namespace Csv;

class Csv implements CsvInterface
{
    use AttributesTrait;

    const DEFAULT_COLUMN_DELIMITER = ",";
    const DEFAULT_ROW_DELIMITER    = "\n";
    const DEFAULT_ENCLOSURE        = '"';

    private $position = 0;
    private $rows = [];

    public function __construct($csv = null)
    {
        if (!is_null($csv)) $this->parse($csv);
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function setRows(array $array = [])
    {
        $this->rows = self::removeEmptyRows($array);

        return $this;
    }

    public function parse($csv)
    {
        if ($csv instanceof Csv) {
            $csv = $csv->getRows();
        } else if (!is_array($csv)) {
            $parser = new Parser($this->getRowDelimiter(), $this->getColumnDelimiter(), $this->getEnclosure());
            $csv    = $parser->parse($csv);
        }

        return $this->setRows($csv);
    }

    public function count()
    {
        return count($this->rows);
    }

    public function current()
    {
        return $this->rows[$this->position];
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->rows[$this->position]);
    }

    public static function removeEmptyRows(array $rows)
    {
        return array_filter($rows, function ($row) {
            return !empty($row);
        });
    }

}