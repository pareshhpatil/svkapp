<?php

require 'config.php';

// DB table to use
$table = 'customer';

// Table's primary key
$primaryKey = 'customer_id';

$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
$columns = array(
    array('db' => 'service_id', 'dt' => 0),
    
    array('db' => 'service_name', 'dt' => 1),
    array('db' => 'amount', 'dt' => 2),
    array('db' => 'customer_code', 'dt' => 3),
    array('db' => 'name', 'dt' => 4),
    array('db' => 'mobile', 'dt' => 5)
);

$int = 6;
foreach ($_SESSION['display_column'] as $col) {
    $col = str_replace('.', '', $col);
    $col = str_replace(' ', '_', $col);
    $col = '__' . $col;
    $columns[] = array('db' => $col, 'dt' => $int);
    $int++;
}

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
require( 'cablesubscription.class.php' );

$column_name = $_SESSION['db_column'];
$column_name = implode('~', $column_name);

echo json_encode(
        SSP::simple($_POST, $merchant_id, $column_name, $sql_details, $table, $primaryKey, $columns)
);
?>