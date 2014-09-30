<?php

namespace Csv;

class Csv implements \Countable, \Iterator
{
    const DEFAULT_COLUMN_DELIMITER = ",";
    const DEFAULT_ROW_DELIMITER    = "\n";
    const DEFAULT_ENCLOSURE        = '"';

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var array
     */
    private $rows = [];

    /**
     * @var string
     */
    private $columnDelimiter;

    /**
     * @var string
     */
    private $rowDelimiter;

    /**
     * @var string
     */
    private $enclosure;

    /**
     * @var bool
     */
    private $useFirstRowAsKeys = false;

    /**
     * @param string|array|CsvInterface|null $csv
     * @param bool $useFirstRowAsKeys
     * @param string $rowDelimiter
     * @param string $columnDelimiter
     * @param string $enclosure
     */
    public function __construct($csv = null, $useFirstRowAsKeys = false, $rowDelimiter = self::DEFAULT_ROW_DELIMITER, $columnDelimiter = self::DEFAULT_COLUMN_DELIMITER, $enclosure = self::DEFAULT_ENCLOSURE)
    {
        $this->setUseFirstRowAsKeys($useFirstRowAsKeys);
        $this->setRowDelimiter($rowDelimiter);
        $this->setColumnDelimiter($columnDelimiter);
        $this->setEnclosure($enclosure);

        if (!is_null($csv)) $this->parse($csv);
    }

    /**
     * @param string|array|CsvInterface $csv
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function parse($csv)
    {
        if ($csv instanceof CsvInterface) $csv = $csv->getRows();
        if (is_array($csv)) return $this->setRows($csv);

        if (!is_string($csv)) throw new \InvalidArgumentException();

        return
            is_file($csv)
                ? $this->parseFile($csv)
                : $this->parseString($csv);
    }

    /**
     * @param string $path
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function parseFile($path)
    {
        if (!(is_file($path) && is_readable($path))) throw new \InvalidArgumentException($path . " doesn't appear to be a readable file.");
        $contents = @file_get_contents($path);

        if (!$contents) throw new \RuntimeException();

        return $this->parseString($contents);
    }

    /**
     * @param string $csv
     * @return $this
     */
    public function parseString($csv)
    {

        $csv = is_string($csv) ? trim($csv) : "";
        if (empty($csv)) return $this;

        $rowdel = $this->getRowDelimiter();
        $coldel = $this->getColumnDelimiter();
        $enc    = $this->getEnclosure();

        $rows = str_getcsv($csv, $rowdel, $enc);
        $rows = array_map(function ($row) use ($coldel, $enc) {
            return str_getcsv($row, $coldel, $enc);
        }, $rows);

        return $this->setRows($rows);
    }

    /**
     * @param string $path
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function save($path)
    {
        if (!is_string($path) || empty($path)) throw new \InvalidArgumentException();

        return @file_put_contents($path, $this->getString()) !== false;
    }

    /**
     * @param array $rows
     * @return $this
     */
    public function setRows(array $rows = [])
    {
        $rows = array_filter($rows, function ($row) {
            return !empty($row);
        });

        if ($this->getUseFirstRowAsKeys() && !empty($rows)) {
            $first = array_shift($rows);
            $rows  = array_map(function ($row) use ($first) {
                return array_combine($first, $row);
            }, $rows);
        }

        $this->rows = $rows;

        return $this;
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @return string
     */
    public function getString()
    {
        $rows = $this->getRows();
        if (empty($rows)) return "";

        $keys = array_keys($rows[0]);
        if (array_diff($keys, array_keys($keys)) != []) array_unshift($rows, $keys);

        $rowdel = $this->getRowDelimiter();
        $coldel = $this->getColumnDelimiter();
        $enc    = $this->getEnclosure();

        $rows = array_map(function ($row) use ($coldel, $enc) {

            if (!empty($enc)) {
                $row = array_map(function ($column) use ($enc) {
                    return $enc . $column . $enc;
                }, $row);
            }

            return implode($coldel, $row);

        }, $rows);

        return implode($rowdel, $rows);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getString();
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setUseFirstRowAsKeys($value)
    {
        $this->useFirstRowAsKeys = (bool) $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUseFirstRowAsKeys()
    {
        return $this->useFirstRowAsKeys;
    }

    /**
     * @param string $delimiter
     * @return $this
     */
    public function setColumnDelimiter($delimiter)
    {
        $this->columnDelimiter = $delimiter;

        return $this;
    }

    /**
     * @return string
     */
    public function getColumnDelimiter()
    {
        return $this->columnDelimiter;
    }

    /**
     * @param string $delimiter
     * @return $this
     */
    public function setRowDelimiter($delimiter)
    {
        $this->rowDelimiter = $delimiter;

        return $this;
    }

    /**
     * @return string
     */
    public function getRowDelimiter()
    {
        return $this->rowDelimiter;
    }

    /**
     * @param string $enclosure
     * @return $this
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = is_string($enclosure) ? trim($enclosure) : "";

        return $this;
    }

    /**
     * @return string
     */
    public function getEnclosure()
    {
        return $this->enclosure;
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
}