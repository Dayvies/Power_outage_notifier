 <html>
<head>
  <title>Comment on Power Outage Status </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>


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
          <li class="nav-item">
            <a class="nav-link" href="/sensloc/map.php">Map</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="/sensloc/index.php">Record</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" style="color:teal"  href="/comment/comment.php">Status</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/about.php">About</a>
          </li>
        </ul>
      </div>
    </div>
<div class="container" style="width: 100%;" style="background-color:#cbc4b7; ">
  <h3 align="center">Comment on the power outage </h3>
  <br/>
  <div class="panel panel-default" style="background-color:#bccac9; padding:1vw;">
    <div class="panel-heading">Sample Data</div>
    <div class="panel-body">
      <div class="table-responsive"  >
        <table id="blacksites" style="background-color:white;" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>UID</th>
              <th>Status</th>
              <th>BlueP</th>
              <th>YellowP</th>
              <th>RedP</th>
              <th>Incomment</th>
              <th>Feedback</th>

            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<br/>
<br/>
</body>
</html>
<script type="text/javascript" language="javascript">
var urlstring= window.location.href;
var url=new URL(urlstring);
var duid =url.searchParams.get("duid");
console.log("duid");
var fetch= "fetch.php"
if(duid!=null)
{
  fetch="fetch.php?duid="+duid;
}
console.log(fetch);
$(document).ready(function(){
  var dataTable=$('#blacksites').DataTable(
    {
      "processing":true,
      "serverSide":true,
      "order":[],
      "ajax":{
        url:fetch,
        type:"POST"
      }

    });
  $('#blacksites').on('draw.dt',function(){
    $('#blacksites').Tabledit({
      url:'action.php',
      dataType:'json',
      columns:{
        identifier:[0,"uid"],
        editable:[[1,"status"],[5,"scomment"],[6,"cus_feed"]]
      },

      restoreButton:false,
      onSuccess:function(data,textStatus,jqXHR)
      {
        if(data.action=='delete')
        {
          $('#'+data.uid).remove();
          $('#blacksites').DataTable().ajax.reload();
        }
      }
    });

  });
  setInterval( function () {

        $('#blacksites').DataTable().ajax.reload( null, false ); // user paging is not reset on reload
  }, 20000 );
});
</script>
