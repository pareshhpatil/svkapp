<?php

require 'config.php';

// DB table to use
$table = 'customer';

// Table's primary key
$primaryKey = 'customer_id';


$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
$columns = array(
    //array('db' => 'customer_id', 'dt' => 0),
    //array('db' => 'customer_code', 'dt' => 0),
    array('db' => 'name', 'dt' => 0),
    array('db' => 'email', 'dt' => 1),
    array('db' => 'mobile', 'dt' => 2)
);
$int = 3;
foreach ($_SESSION['display_column'] as $col) {
    $col = str_replace('.', '', $col);
    $col = str_replace(' ', '_', $col);
    $col = '__' . $col;
    $columns[] = array('db' => $col, 'dt' => $int);
    $int++;
}

$columns[] = array('db' => 'created_date', 'dt' => $int, 'datatype' => 'specialDate');
$int++;
$columns[] = array('db' => 'payment_status', 'dt' => $int);
$int++;
$columns[] = array('db' => 'customer_id', 'dt' => $int);

// SQL server connection information
$sql_details = array(
    'user' => $dbArr['USER'],
    'pass' => $dbArr['CRED'],
    'db' => $dbArr['SCHEMA'],
    'host' => $dbArr['URL']
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
require( 'customer_list.class.php' );
SSP::$action_coll = $int;
$column_name = $_SESSION['db_column'];

$column_name = implode('~', $column_name);
echo json_encode(
        SSP::simple($_POST, $merchant_id, $column_name, $sql_details, $table, $primaryKey, $columns)
);
?>