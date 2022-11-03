<!DOCTYPE html>
<html lang ="en">
<head>
  <meta charset ="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style type ="text/css">
    body{ font: normal 14px Verdana;}
    h1{ font-size: 24px;}
    h2{font-size:18px;}
    #sidebar {float:right; width:30px}
    #main{padding-right:15px;}
    .infowindow{width:220px}

  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnD7obafvgMFAlw8bqQb26FmF_yu07uEM&callback=initMap"></script>
  <script type="text/javascript">
  var count=0;
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
  var clustmarker =[];
    var map;
    var center = {lat: -1.101465, lng:37.011867 };
    var purple_icon = {
    url:'http://www.clker.com/cliparts/k/w/Q/w/L/m/map-marker-location.svg' // url
 // anchor
};
    //var purple_icon =  'http://maps.google.com/mapfiles/ms/icons/purple-dot.png' ;

  // var purple_icon ='https://upload.wikimedia.org/wikipedia/commons/f/fb/Map_pin_icon_green.svg';
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
purple_icon = {
  url:'ico3.png',
//url:'http://www.clker.com/cliparts/k/w/Q/w/L/m/map-marker-location.svg.thumb.png', // url
//scaledSize: new google.maps.Size(100, 100), // scaled size
//origin: new google.maps.Point(0,0), // origin
//anchor: new google.maps.Point(0, 0) // anchor
};
makeRequest('get_senslocations.php',function(data){
  var data= JSON.parse(data.responseText);
  for (var i=0;i<data.length;i++){
    displayLocation(data[i]);
  }
});
makeRequest('userloc.php',function(data2){
  var data2= JSON.parse(data2.responseText);
  for (var i=0;i<data2.length;i++){
    displayLocation2(data2[i]);
  }
});
}

  function displayLocation(location){
    var content ='<div class="infowindow"<strong>' +location.uid+'</strong>'
    +'<br/><a href="/comment/comment.php?duid='+location.uid+'">Comment</a><br/>' + location.description +'</div>';
    /*if (parseInt(location.lat)==0){
        var geocoder = new google.maps.Geocoder();
      geocoder.geocode({'address':location.address},function(results,status){
        if (status==google.maps.GeocoderStatus.OK){
          var marker = new google.maps.Marker({
            map:map,
            position:results[0].geometry.location,
            title:location.name
          } );

    google.maps.infowindow.addListener('click',function(){
       infowindow.setContent(content);
        infowindow.open(map,marker);
      });
        }
      });
    }*/
   //else{
     var infowindow = new google.maps.InfoWindow({
       content :'<div class="infoWindow"<strong> Hello There</strong></div>'
     });

    let position1 ={lat:parseFloat(location.lat),lng:parseFloat(location.lon)};
    console.log(position1);
       console.log(location.uid);
       if (location.status=="OFF"){
  var marker= new google.maps.Marker({
        map:map,
       position:position1,

       icon:{
      path: google.maps.SymbolPath.CIRCLE,
      scale: 10,
    }  ,
       label:"TX1",
      title:location.uid,

    });
    google.maps.event.addListener(marker, 'click', function() {
infowindow.setContent(content);
infowindow.open(map,marker);
});
  }
  else{
    const marker= new google.maps.Marker({
          map:map,
         position:position1,
         label:"TX",
        title:location.uid,

      });
      google.maps.event.addListener(marker, 'click', function() {
  infowindow.setContent(content);
  infowindow.open(map,marker);
  });
  }

//}


  }

  function displayLocation2(location){
    var content ='<div class="infowindow"<strong>' +location.uid+'</strong>'
    +'<br/><a href="/comment/comment.php?duid='+location.uid+'">Comment</a><br/>' + location.description +'</div>';

     var infowindow = new google.maps.InfoWindow({
       content :'<div class="infoWindow"<strong> Hello There</strong></div>'
     });

    let position1 ={lat:parseFloat(location.lat),lng:parseFloat(location.lon)};
    console.log(location.username);
    var markerT2=location.username;
    var markerT=location.username;
    markerT=markerT.replace(/\s/g, '#');
    console.log(markerT);
       if (location.lat!=0&&location.sensor=='pAllocate'){
  var marker= new google.maps.Marker({
        map:map,
       position:position1,
       id:markerT,
       html: '<div class="infowindow"<strong>' +markerT2+'</strong>'
       +'<br/><p >Allocate Sensor </p>'+'<input id="'+markerT+'" type="text" /><button value="allocate" onclick=allocateSensor("'+markerT+'")>Allocate</button>'+'</div>',
    icon:purple_icon,

      title:location.username,

    });
  //  marker
    clustmarker[markerT]=marker;
    bindMarkerinfo(marker,markerT);
    count=count+1;
    console.log(count);
  }
  refreshTab();

//}


}
var bindMarkerinfo = function(marker,markerId) {
                google.maps.event.addListener(marker, "click", function (point) {
                     // get marker id by using clicked point's coordinate
                    var marker = clustmarker[markerId]; // find marker
                    marker.infowindow = new google.maps.InfoWindow();
                    marker.infowindow.setContent(marker.html);
                    marker.infowindow.open(map, marker);
                    console.log("hey");
                    // removeMarker(marker, markerId); // remove it
                });}
function allocateSensor(markerId){
var str=  document.getElementById(markerId).value;
var z=markerId.replace(/#/g,' ');
console.log(z);
$.ajax(
  {
    type:"POST",
    url:"allocate.php",
    data:"username="+z+"&sensor="+str,
    success: function (msg){
    response(msg,z,str,markerId);
    }
  }
);


}
function response(msg,user,sensor,markerid){
if(msg=="Values Inserted in MySql Database Table")
{
  var marker=clustmarker[markerid]
  marker.html='<div class="infowindow"<strong>' +user+'</strong>'
  +'<br/><p >Allocated Sensor </p>'+'<br/><p>'+sensor+'</div>'

  marker.infowindow.setContent(marker.html);
  count=count-1;
  refreshTab();
  setTimeout(function(){marker.infowindow.close();},3000);
  setTimeout(function(){  marker.setMap(null); // set markers setMap to null to remove it from map
    delete clustmarker[markerid]; // delete marker instance from markers object;
  },5000);
}
}



    $(document).ready(function(){

    }
  );
  /*function refreshTable(){
    $('#tableHolder').load('status.php',function(){
      setTimeout(refreshTable,100000000);
    });*/
    function refreshTab(){
      console.log(count);
         if(count==0){
        $('#tableHolder').html('<div style="padding:20px;"><strong>No Customer Needs Allocation Currently</strong></div>');

      }
      else if (count==1)
    {
        $('#tableHolder').html('<div style="padding:20px;"><strong>'+count+' Customer Needs Allocation Currently</strong></div>');

    }
    else if (count>1)
  {
      $('#tableHolder').html('<div style="padding:20px;"><strong>'+count+' Customers Need Allocation Currently</strong></div>');

  }


    }

    </script>
    </head>
    <body style="background-color:#cbc4b7;">

      <div>
        <nav class="navbar sticky-top navbar-expand-lg navbar-dark " style="background-color: #0e2433;">
      <a class="navbar-brand px-3" href="#">Auto Power Notifier</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="/index.html">Home</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" style="color:teal" href="/sensloc/map.php">Map</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="/sensloc/index.php">Record</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/comment/comment.php">Status</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
        </ul>
      </div>
    </nav>
    <br>
    </div>

    <section id="main">
      <div id="map" style="width: 95%; height:500px;"></div>
      </section>

      <div id="tableHolder"></div>
      </body>
      </html>
