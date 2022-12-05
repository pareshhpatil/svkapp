<?php

require 'config.php';

$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
$columns = array(
    array('db' => 'id', 'dt' => 0),
    array('db' => 'bill_date', 'dt' => 1, 'datatype' => 'date'),
    array('db' => 'due_date', 'dt' => 2, 'datatype' => 'date'),
    array('db' => 'customer_code', 'dt' => 3),
    array('db' => 'name', 'dt' => 4),
    array('db' => 'absolute_cost', 'dt' => 5),
);

$int = 6;
$columns[] = array('db' => 'payment_request_id', 'dt' => $int);

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
require( 'bulkrequest.class.php' );

SSP::$user_id = $user_id;
SSP::$action_coll = $int;
SSP::$bulk_id = ($_SESSION['_bulk_id'] > 0) ? $_SESSION['_bulk_id'] : 0;
echo json_encode(
        SSP::simple($_POST, $sql_details, $columns)
);
?>