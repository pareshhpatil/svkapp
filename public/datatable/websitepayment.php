<?php

require 'config.php';

$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
// SQL server connection information
$sql_details = array(
    'user' => $dbArr['USER'],
    'pass' => $dbArr['CRED'],
    'db' => $dbArr['SCHEMA'],
    'host' => $dbArr['URL']
);
$int = 0;
$columns[] = array('db' => 'date', 'dt' => $int, 'datatype' => 'specialDate');
$int++;
$columns[] = array('db' => 'xway_transaction_id', 'dt' => $int);
$int++;

if ($_SESSION['_has_franchise'] == 1) {
    $columns[] = array('db' => 'franchise_name', 'dt' => $int);
    $int++;
}
$columns[] = array('db' => 'email', 'dt' => $int);
$int++;
$columns[] = array('db' => 'mobile', 'dt' => $int);
$int++;
$columns[] = array('db' => 'reference_no', 'dt' => $int);
$int++;
$columns[] = array('db' => 'customer_name', 'dt' => $int);
$int++;
$columns[] = array('db' => 'amount', 'dt' => $int, 'datatype' => 'money');
$int++;
if ($_SESSION['_type'] == 1) {
    $columns[] = array('db' => 'udf1', 'dt' => $int);
    $int++;
    $columns[] = array('db' => 'udf2', 'dt' => $int);
    $int++;
    $columns[] = array('db' => 'udf3', 'dt' => $int);
    $int++;
    $columns[] = array('db' => 'udf4', 'dt' => $int);
    $int++;
    $columns[] = array('db' => 'udf5', 'dt' => $int);
}
if ($_SESSION['_type'] == 3) {
    $columns[] = array('db' => 'udf1', 'dt' => $int);
    $int++;
    $columns[] = array('db' => 'description', 'dt' => $int);
}






/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
require('websitepayment.class.php' );

SSP::$merchant_id = $merchant_id;
SSP::$franchise_id = $_SESSION['_franchise_id'];
SSP::$from_date = $_SESSION['_from_date'];
SSP::$to_date = $_SESSION['_to_date'];
SSP::$type = $_SESSION['_type'];


echo json_encode(
        SSP::simple($_POST, $sql_details, '', $table, $primaryKey, $columns)
);
?>