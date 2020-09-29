<?php

require_once 'InBd/Connection.php';

$connection = new Connection();

$price = $_POST['Price'];

$ot = $_POST['ot'];

$do = $_POST['do'];

$znak = $_POST['znak'];

$quantity = $_POST['quantity'];

$sql = 'SELECT * FROM products.product WHERE (in_stock_1+in_stock_2)<? AND ?<cost AND cost<?';

$request = $connection->link->prepare($sql);
$request->bind_param('iii', $quantity, $ot, $do);
$request->execute();

$resultSet = $request->get_result();

//print_r($price);die;

print_r($resultSet->fetch_all());

