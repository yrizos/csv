<?php

namespace Csv;

class Parser implements ParserInterface
{
    use AttributesTrait;

    private $duration = 0;

    public function __construct(
        $rowDelimiter = Csv::DEFAULT_ROW_DELIMITER,
        $columnDelimiter = Csv::DEFAULT_COLUMN_DELIMITER,
        $enclosure = Csv::DEFAULT_ENCLOSURE
    )
    {
        $this->setColumnDelimiter($columnDelimiter)
            ->setRowDelimiter($rowDelimiter)
            ->setEnclosure($enclosure);
    }

    public function parse($csv)
    {
        if (is_file($csv)) return $this->parseFile($csv);

        return $this->parseString($csv);
    }

    protected function parseFile($path)
    {
        if (!(is_file($path) && is_readable($path))) throw new \InvalidArgumentException($path . " doesn't appear to be a readable file.");
        $contents = @file_get_contents($path);

        return $this->parseString($contents);
    }

    protected function parseString($string)
    {
        $string = is_string($string) ? trim($string) : false;
        if (!$string) return [];

        $time = microtime(true);
        $rows = explode($string, $this->getRowDelimiter());
        $rows = Csv::removeEmptyRows($rows);

        $cdel = $this->getColumnDelimiter();
        $enc  = $this->getEnclosure();
        $rows = array_map(
            function ($row) use ($cdel, $enc) {
                return str_getcsv($row, $cdel, $enc);
            },
            $rows
        );

        $this->duration = microtime(true) - $time;

        return $rows;
    }

    public function getParseDuration()
    {
        return $this->duration;
    }


}