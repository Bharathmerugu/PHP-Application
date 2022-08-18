<?php

  // This file is used to establish a connection with your MySQL Database.
  // We are using the Portable Data Objects library in PHP (see https://www.php.net/manual/en/book.pdo.php) to create
  // a database connection object. This library provides a data-access abstraction laye, which means you can use the 
  // methods to issue queries and fetch data regardless of the database you are using. 

  // Include this file in your php file where you will be calling database methods.

  $dbname = "ecommerce"; // replace this with the name of your MySQL database

  try {
    $db = new PDO("mysql:dbname=$dbname; host=localhost","dbuser","password"); 
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Comment the statement below
    // print "Connection Successful";

  } catch(PDOException $e) {
    echo $e->getMessage();
  }
?>
