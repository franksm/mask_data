<?php

require_once __DIR__ . "/vendor/autoload.php";
$climate = new \League\CLImate\CLImate();

if (count($argv) == 1) {
    assert(count($argv) > 1, "沒有添加地址參數 請輸入: php climate_show.php 位置");
    exit;
}

#參考: https://stackoverflow.com/questions/4801895/csv-to-associative-array
$array = $fields = array();
$i = 0;
$handle = fopen("maskdata.csv", "r");
if ($handle) {
    while (($row = fgetcsv($handle, 4096)) !== false) {
        if (empty($fields)) {
            $fields = $row;
            continue;
        }
        if (strpos($row[2], $argv[1]) === false) {
            continue;
        }
        foreach ($row as $k => $value) {
            $array[$i][$fields[$k]] = $value;
        }
        $i++;
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

if (count($array) == 0) {
    assert(count($array) > 0, '查無資料,請重新給予新的地址參數');
} else {
    $climate->table($array);
}
