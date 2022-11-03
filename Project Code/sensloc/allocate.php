<?php
$host = "localhost";
$dbname="check data";
$username="Davy";
$password ="project123";
$conn= new mysqli($host,$username,$password,$dbname);

$username=$_POST["username"];
$sensor=$_POST["sensor"];
//$username='Davies Momanyi';
//$sensor="TX10";
//check if connection has been established succesfully
if ($conn->connect_error){
  die("connection failed " .$conn->connect_error);
}
;
$sqlchecksensor="SELECT * FROM `senloc` WHERE `uid` LIKE '".$sensor."'";
$sensorQuery=mysqli_query($conn,$sqlchecksensor);
if (mysqli_num_rows($sensorQuery)>0)
{
  $sql= " UPDATE user_table SET sensor='".$sensor."'
  WHERE username='".$username."' " ;
      if($conn->query($sql)===TRUE){
        echo "Values Inserted in MySql Database Table";
      }
      else {
        echo "something wrong with username";
      }
}
else{
  echo ("Wrong Sensor");
}
