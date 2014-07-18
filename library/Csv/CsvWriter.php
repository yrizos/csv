<?php

namespace Csv;

class CsvWriter
{

    public function __construct(array $rows = array(), array $keys = array(), $columnDelimiter = ",", $rowDelimiter = "\n", $enclosure = '"', $skipEmptyRows = true)
    {
        $this->setColumnDelimiter($columnDelimiter)
            ->setRowDelimiter($rowDelimiter)
            ->setEnclosure($enclosure)
            ->setSkipEmptyRows($skipEmptyRows)
            ->setRows($rows)
            ->setKeys($keys);
    }

    public function getCsv(array $rows = array())
    {
        if (!empty($rows)) $this->setRows($rows);

        $rows = $this->getNormalizedRows();
        $csv  = $this->generateCsv($rows);

        return $csv;
    }

    private function getNormalizedRows()
    {
        $rows = $this->getRows();
        $keys = $this->getKeys();

        if (!empty($keys)) array_unshift($rows, $keys);

        foreach ($rows as $key => $column) {
            if (
                !is_array($column)
                || (
                    empty($column)
                    && $this->getSkipEmptyRows()
                )
            ) {
                unset($rows[$key]);
            }
        }

        return array_values($rows);
    }

    private function generateCsv(array $rows = array())
    {
        $columnDelimiter = $this->getColumnDelimiter();
        $rowDelimiter    = $this->getRowDelimiter();
        $enclosure       = $this->getEnclosure();

        foreach ($rows as $keyRow => $column) {
            foreach ($column as $keyColumn => $cell) {
                if (!$enclosure) continue;

                $rows[$keyRow][$keyColumn] = $enclosure . trim($cell) . $enclosure;
            }

            $rows[$keyRow] = implode($columnDelimiter, $rows[$keyRow]);
        }

        return implode($rowDelimiter, $rows);
    }

    public function setRows(array $rows = array())
    {
        $this->rows = array_values($rows);

        return $this;
    }

    public function setKeys(array $keys = array())
    {
        $this->keys = $keys;

        return $this;
    }

    public function __toString()
    {
        return $this->getCsv();
    }

}