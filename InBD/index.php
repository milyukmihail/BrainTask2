<?php

require_once("Connection.php");

include("Classes/PHPExcel.php");

$connection = new Connection();

$objPHPExcel = PHPExcel_IOFactory::load("pricelist.xls");

$name = [];
$cost = [];
$cost_wholesale = [];
$in_stock_1 = [];
$in_stock_2 = [];
$country_of_origin = [];

foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
    $lastRow = $worksheet->getHighestRow();
    $lastColumn = $worksheet->getHighestColumn();
    $lastColumnIndex = PHPExcel_Cell::columnIndexFromString($lastColumn);

    for ($row = 2; $row <= $lastRow; ++$row) {
        for ($col = 0; $col < $lastColumnIndex - 2; ++$col) {
            $val = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            if ($col == 0) {
                $name [] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            } elseif ($col == 1) {
                $cost [] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            } elseif ($col == 2) {
                $cost_wholesale [] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            } elseif ($col == 3) {
                $in_stock_1 [] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            } elseif ($col == 4) {
                $in_stock_2 [] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            } elseif ($col == 5) {
                $country_of_origin [] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            };
        };
    };
};

$query ='INSERT INTO product (name, cost, cost_wholesale, in_stock_1, in_stock_2, country_of_origin) VALUES ';
for($i = 0; $i <= 998; $i++) {
    $query .= "('$name[$i]', '$cost[$i]', '$cost_wholesale[$i]', '$in_stock_1[$i]', '$in_stock_2[$i]', '$country_of_origin[$i]'), ";
}
$query = mb_substr($query, 0, -2);

$result = mysqli_query($connection->link, $query) or die("Ошибка " . mysqli_error($connection->link));
$result = rtrim($result, ',');
mysqli_query($connection->link, $result);

