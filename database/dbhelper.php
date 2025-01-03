<?php
require_once('config.php');

function execute($sql) {
    // Create connection
    $conn = mysqli_connect($GLOBALS['host'], 
                         $GLOBALS['username'], 
                         $GLOBALS['password'], 
                         $GLOBALS['database']);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Set charset to utf8
    mysqli_set_charset($conn, 'UTF8');

    // Execute query
    $resultset = mysqli_query($conn, $sql);
    
    // Close connection
    mysqli_close($conn);

    return $resultset;
}

function executeResult($sql, $isSingle = false) {
    $conn = mysqli_connect($GLOBALS['host'], 
                         $GLOBALS['username'], 
                         $GLOBALS['password'], 
                         $GLOBALS['database']);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    mysqli_set_charset($conn, 'UTF8');

    $resultset = mysqli_query($conn, $sql);
    if (!$resultset) {
        die("Query failed: " . mysqli_error($conn));
    }

    if ($isSingle) {
        $data = mysqli_fetch_array($resultset, 1);
    } else {
        $data = [];
        while (($row = mysqli_fetch_array($resultset, 1)) != null) {
            $data[] = $row;
        }
    }

    mysqli_close($conn);

    return $data;
}

function executeSingleResult($sql)
{
	//save data into table
	// open connection to database
	$con = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    mysqli_set_charset($con, 'UTF8');
	//insert, update, delete
	$result = mysqli_query($con, $sql);
	$row    = mysqli_fetch_array($result, 1);

	//close connection
	mysqli_close($con);

	return $row;
}



