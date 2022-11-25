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
$table = 'booking_slots';

// Table's primary key
$primaryKey = 'slot_id';

$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
$columns = array(
    array('db' => 'slot_id', 'dt' => 0),
    array('db' => 'slot_date', 'dt' => 1, 'datatype' => 'date'),
    array('db' => 'package_name', 'dt' => 2),
    array('db' => 'slot_time_from', 'dt' => 3, 'datatype' => 'time'),
    array('db' => 'slot_time_to', 'dt' => 4, 'datatype' => 'time'),
    array('db' => 'slot_price', 'dt' => 5),
    array('db' => 'total_seat', 'dt' => 6),
    array('db' => 'available_seat', 'dt' => 7),
    array('db' => 'slot_available', 'dt' => 8),
    array('db' => 'slot_available', 'dt' => 9)
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
require( 'slot.class.php' );


echo json_encode(
        SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns)
);
