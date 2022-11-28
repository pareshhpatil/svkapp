<?php

require 'config.php';

// DB table to use
$table = 'customer_service';

// Table's primary key
$primaryKey = 'id';

$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
$columns = array(
    array('db' => 'id', 'dt' => 0),
    array('db' => 'updated_date', 'dt' => 1),
    array('db' => 'customer_name', 'dt' => 2),
    array('db' => 'customer_code', 'dt' => 3),
    array('db' => 'name', 'dt' => 4),
    array('db' => 'cost', 'dt' => 5),
    array('db' => 'expiry_date', 'dt' => 6),
    array('db' => 'status', 'dt' => 7),
    array('db' => 'action', 'dt' => 8)
);



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
require( 'settopbox.class.php' );

$column_name = $_SESSION['db_column'];
$column_name = implode('~', $column_name);

echo json_encode(
        SSP::simple($_POST, $merchant_id, $column_name, $sql_details, $table, $primaryKey, $columns)
);
?>