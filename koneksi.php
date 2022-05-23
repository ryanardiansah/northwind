<?php
/**
 * using mysqli_connect for database connection
 */
 
$databaseHost = 'localhost';
$databaseName = 'northwind';
$databaseUsername = 'root';
$databasePassword = '';
 
$kon = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 
 
?>