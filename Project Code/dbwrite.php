<?php
$host = "localhost";
$dbname="check data";
$username="Davy";
$password ="project123";
$conn= new mysqli($host,$username,$password,$dbname);

//check if connection has been established succesfully
if ($conn->connect_error){
  die("connection failed " .$conn->connect_error);
}
else{
  echo "connected to mysql database. ";
}
//$_POST is a PHP Superglobal that assists us to collect/access the data, that arrives in the form of a post request made to this script.
  // If values sent by NodeMCU are not empty then insert into MySQL database table
if (!empty($_POST['uidl'])){
  $bluep=$_POST['bluepl'];
  $redp=$_POST['redpl'];
  $yellowp=$_POST['yellowpl'];
  $status=$_POST['statusl'];
  $uid=$_POST['uidl'];

  if (status=="ON")
  {
    $scomment=" ";
    $cusfeed=" ";
    $sql= " UPDATE senloc SET status='".$status."',
    bluep='".$bluep."',
    yellowp='".$yellowp."',
    redp='".$redp."',
    cus_feed='".$cusfeed."',
    scomment='".$scomment."'
    WHERE uid='".$uid."' " ;
        if($conn->query($sql)===TRUE){
          echo " Values Inserted in MySql database table ";
        }
        else {
          echo "Error: ". $sql . "<br>" .$conn-> error;
        }
  }
  else if($status=="OFF")
  {
    $scomment=" ";
    $cusfeed=" ";
    $sql= " UPDATE senloc SET status='".$status."',
    bluep='".$bluep."',
    yellowp='".$yellowp."',
    redp='".$redp."'

    WHERE uid='".$uid."' " ;
        if($conn->query($sql)===TRUE){
          echo " Values Inserted in MySql database table ";
        }
        else {
          echo "Error: ". $sql . "<br>" .$conn-> error;
        }
  }
  else {
    echo "Error: ". $sql . "<br>" .$conn-> error;
  }

}
$conn->close();
