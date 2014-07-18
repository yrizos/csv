<?php

namespace Csv;


interface CsvInterface extends AttributesInterface, \Countable, \Iterator
{
    public function parse($csv);

    public function setRows(array $rows);

    public function getRows();
} 