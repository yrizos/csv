Simple library to work with CSV files.


Usage
-----

```php
$csv = new \Csv\Csv("path/to/your.csv");

foreach($csv as $row) print_r($row);

$csv->setColumnDelimiter("\t");
$csv->save("path/to/your.tsv");
```

