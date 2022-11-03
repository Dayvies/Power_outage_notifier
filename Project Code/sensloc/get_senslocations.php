<?php
$host = "localhost";
$dbname="check data";
$username="Davy";
$password ="project123";
$conn= new mysqli($host,$username,$password,$dbname);
//checking if connection has been established
if ($conn->connect_error){
  die("connection failed " .$conn->connect_error);
}

$sql= "SELECT * FROM senloc ";
$result=$conn->query($sql);
$locations =$result->fetch_all(MYSQLI_ASSOC);
echo json_encode($locations);
