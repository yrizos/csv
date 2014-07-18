<?php

namespace Csv;

interface AttributesInterface
{
    public function setColumnDelimiter($delimiter);

    public function getColumnDelimiter();

    public function setRowDelimiter($delimiter);

    public function getRowDelimiter();

    public function setEnclosure($enclosure);

    public function getEnclosure();
}