<?php
$server = "localhost";
$username ="Davy";
$password = "project123";
$database= "check data";
$dsn     ="mysql:host=$server;dbname=$database";
  $connect = new PDO($dsn,$username,$password);
