<!DOCTYPE html>
<html lang="en">
<head>
  <title> Record Sensor </title>
  <style type="text/css">
  a.button{
    -webkit-appearance:button;
    -moz-appearance:button;
    appearance: button;
    text-decoration:none;
    color:white;
    padding: 15px 25px 25px 25px;
    background-color:teal;
  }
  #form{
    padding: 0px 25px 25px 25px;
    float:right;
  }
  #wrapper{
     display:flex;
       padding: 50px 25px 25px 25px;
         background-color:gray;

   }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnD7obafvgMFAlw8bqQb26FmF_yu07uEM&callback=initMap"></script>
  <script type="text/javascript">
  function makeRequest(url,callback){

    var request;
    if (window.XMLHttpRequest){
      request=new XMLHttpRequest();//Chrome , Safari , Firefox
    }
    else{
      request = new ActiveXObject("Microsoft.XMLHTTP");// IE6,IE5
    }
    request.onreadystatechange=function(){
      if (request.readyState==4 && request.status == 200){
        callback(request);
      }
    }
    request.open("GET",url,true);
    request.send();
  }
    var map;
    var center = {lat: -1.101465, lng:37.011867 };


function initMap(){
  var mapOptions={
    zoom:15,
    center: center,
    mapId:'5028ea12062d5f68'
  }
  map=new google.maps.Map(document.getElementById("map"),mapOptions);
 //var marker = new google.maps.Marker({
   //map:map,
   //position:center,
// });
 var markers=[];
var getMarkerUniqueId= function(lat, lng) {
          return Math.abs(lat) + '' + Math.abs(lng);
      };
var getLatLng = function(lat, lng) {
                return new google.maps.LatLng(lat, lng);
            };

            var addMarker = google.maps.event.addListener(map, 'click', function(e) {
                      var lat = e.latLng.lat(); // lat of clicked point
                      var lng = e.latLng.lng(); // lng of clicked point
                      var markerId = getMarkerUniqueId(lat, lng); // an that will be used to cache this marker in markers object.
                      var marker = new google.maps.Marker({
                          position: getLatLng(lat, lng),
                          map: map,
                          animation: google.maps.Animation.DROP,
                          id: 'marker_' + markerId,
                          html: "    <div id='info_"+markerId+"'>\n" +
                          "<p>Select this location?</p>" +

                          "<p>Location will Change automatically after sensor is turned on</p>" +


                          " </div>"
                      });
                      markers[markerId] = marker; // cache marker in markers object
                      bindMarkerEvents(marker); // bind right click event to marker
                      bindMarkerinfo(marker); // bind infowindow with click event to marker
                      var lat1=lat.toFixed(6);
                      var lng1=lng.toFixed(6);
                      var lat2=lat.toFixed(2);
                      var lng2=lng.toFixed(2);
                      var markerId2 = getMarkerUniqueId(lat2, lng2);
                      document.getElementById('uid').value = "T"+markerId2;

                      document.getElementById('lat').value = lat1;
                      document.getElementById('lon').value = lng1;
                  });
                  var bindMarkerinfo = function(marker) {
                google.maps.event.addListener(marker, "click", function (point) {
                    var markerId = getMarkerUniqueId(point.latLng.lat(), point.latLng.lng()); // get marker id by using clicked point's coordinate
                    var marker = markers[markerId]; // find marker
                    infowindow = new google.maps.InfoWindow();
                    infowindow.setContent(marker.html);
                    infowindow.open(map, marker);
                    // removeMarker(marker, markerId); // remove it
                });
            };
            var bindMarkerEvents = function(marker) {
          google.maps.event.addListener(marker, "rightclick", function (point) {
              var markerId = getMarkerUniqueId(point.latLng.lat(), point.latLng.lng()); // get marker id by using clicked point's coordinate
              var marker = markers[markerId]; // find marker
              removeMarker(marker, markerId); // remove it
          });
      };
      var removeMarker = function(marker, markerId) {
      marker.setMap(null); // set markers setMap to null to remove it from map
      delete markers[markerId]; // delete marker instance from markers object
  };




}





    </script>

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
  <div id="wrapper" >

    <div id="map" style="width: 65%; height:600px;"></div>

  <div id="form">
    <h1 > Record New Sensor Details </h1>
    <form action ="insert.php" method ="post">
      <div class="form-group">
        <label for="uid">Unique Id :</label>
        <input type="text" name="uid" id="uid" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="type">Type:</label>
        <select name="type" id="type" class="form-control">
          <option value = "Transformer">Transformer</option>
          <option value = "Line">Line</option>
        </select>
      </div>
      <div class="form-group">
        <label for="lat">Latitude:</label>
        <input type="number" name="lat" id="lat" step="any" class="form-control">
      </div>
      <div class="form-group">
        <label for="lon">Longitude:</label>
        <input type="number" name="lon" id="lon" step="any" class="form-control">
      </div>
      <div class="form-group" >
        <label for="status">Status:</label>
        <select name="status" id="status" class="form-control">
          <option value = "ON">ON</option>
          <option value = "OFF">OFF</option>
        </select>
      </div>
      <div class="form-group">
        <label for="desc">Description :</label>
        <input type="desc" name="desc" id="desc" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary form-control">Submit</button>
    </form>
  </br>
    <a href="/sensloc/map.php" class = "btn btn-dark">Go to Map </a>
  </div>
  </div>
</body>
</html>
