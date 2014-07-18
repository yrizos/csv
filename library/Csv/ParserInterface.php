<?php

namespace Csv;

interface ParserInterface extends AttributesInterface
{
    public function __construct(
        $rowDelimiter = Csv::DEFAULT_ROW_DELIMITER,
        $columnDelimiter = Csv::DEFAULT_COLUMN_DELIMITER,
        $enclosure = Csv::DEFAULT_ENCLOSURE
    );

    public function parse($csv);
}