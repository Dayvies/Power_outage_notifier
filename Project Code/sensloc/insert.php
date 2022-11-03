<!DOCTYPE html>
<html>
<head>
  <title>Confirm Record Sensor </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style type="text/css">
  a.button{
    -webkit-appearance:button;
    -moz-appearance:button;
    appearance: button;
    text-decoration:none;
    color:white;
    padding: 15px 25px;
    background-color:teal;
  }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>
  <div>
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark " style="background-color: #0e2433;">
  <a class="navbar-brand px-3" href="#">Auto Power Notifier</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="/index.html">Home</a>
      </li>
      <li class="nav-item ">
        <a class="nav-link"  href="/sensloc/map.php">Map</a>
      </li>
      <li class="nav-item active ">
        <a class="nav-link" style="color:teal" href="/sensloc/index.php">Record</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/comment/comment.php">Status</a>
      </li>
      <li class="nav-item ">
        <a class="nav-link"  href="/about.php">About</a>
      </li>
    </ul>
  </div>
</nav>
<br>
</div>
  <div>
    <center>
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
      else{
        echo "connected to mysql database. ";
      }
      $uid=$_REQUEST['uid'];
      $type=$_REQUEST['type'];
      $lat=$_REQUEST['lat'];
      $lon=$_REQUEST['lon'];
      $status=$_REQUEST['status'];
      $desc=$_REQUEST['desc'];

      $sql= "INSERT IGNORE INTO senloc (uid,type,lat,lon,status,bluep,redp,yellowp,description)
      VALUES ('$uid','$type','$lat','$lon','$status','$status','$status','$status','$desc')";

      if(mysqli_query($conn,$sql)){
        echo "<h3>data has been successfully stored</h3>";
        echo nl2br("\n$uid\n  "
           . "$lat\n $lon\n $status \n $desc");
      }
      else{
        echo "ERROR SORRY $sql."
        .mysqli_error($conn);
      }
      // To Close the  Connection
      ?>
    </br>
    <a href="index.php" class = "button">Record a Sensor</a>
    </center>
  </div>
</body>
</html>
