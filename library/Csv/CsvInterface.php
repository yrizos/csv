<?php

namespace Csv;


interface CsvInterface extends \Countable, \Iterator
{

    public function __construct($csv = null);

    public function parse($csv);

    public function parseFile($path);

    public function parseString($csv);

    public function save($path);

    public function setRows(array $rows);

    public function getRows();

    public function getString();

    public function __toString();

    public function setColumnDelimiter($delimiter);

    public function getColumnDelimiter();

    public function setRowDelimiter($delimiter);

    public function getRowDelimiter();

    public function setEnclosure($enclosure);

    public function getEnclosure();

}