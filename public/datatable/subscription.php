<?php

require 'config.php';
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'subscription';

// Table's primary key
$primaryKey = 'subscription_id';

$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
$columns = array(
    array('db' => 'subscription_id', 'dt' => 0),
    array('db' => 'name', 'dt' => 1),
    array('db' => 'invoice_type', 'dt' => 2),
    array('db' => 'start_date', 'dt' => 3, 'datatype' => 'date'),
    array('db' => 'display_text', 'dt' => 4),
    array('db' => 'mode', 'dt' => 5),
    array('db' => 'end_mode', 'dt' => 6),
    array('db' => 'created_date', 'dt' => 7,'datatype' => 'specialDate'),
    array('db' => 'next_bill_date', 'dt' => 8,'datatype' => 'date'),
    array('db' => 'next_bill_date', 'dt' => 9),
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
require( 'subscription.class.php' );

SSP::$merchant_id = $merchant_id;
SSP::$franchise_id = $_SESSION['sub_franchise_id'];

echo json_encode(
        SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns)
);
