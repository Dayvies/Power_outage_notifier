<?php
$host = "localhost";
$dbname="check data";
$username="Davy";
$password ="project123";
$conn= mysqli_connect($host,$username,$password,$dbname);

//check if connection has been established succesfully
if ($conn->connect_error){
  die("connection failed " .$conn->connect_error);
}
else{
  echo "connected to mysql database. ";
}
$result= mysqli_query($conn,"SELECT * FROM locs ORDER BY timestamp  DESC LIMIT 10 ");
echo "
<table border='1' class=\"table table-striped table-hover\">
<tr>
<th>UID</th>
<th>Description</th>
<th>Status</th>
</tr>
";
while($row=mysqli_fetch_array($result))
{
  $href= "/comment/comment.php?duid=".$row['comme'];
  echo "<tr>";
  echo "<td>"  ;
  echo "<a href=".$href.">".$row['comme']."</a></td>";
  echo "<td>".$row['descript']."</td>";
  echo "<td>".$row['statu']."</td>";
  echo "<tr>";

}
echo "</table>";
?>
