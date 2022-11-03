<html>
<head>
 <title>Comment on Power Outage Status </title>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
 <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" />
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
 <script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>

</script>
<script type="text/javascript">
$(document).ready(function(){
  refreshTable();
}
);
function refreshTable(){
$('#tableHolder').load('/sensloc/status.php',function(){
  setTimeout(refreshTable,10000);
});
}
</script>
</head>
</body>
<div class="container">
 <h3 align="center">Sensor Current Status </h3>
 <br/>
 <div class="panel panel default">
   <div class="panel-heading">Sensor Data</div>
   <div id='tableHolder' class="panel-body">
     </div>
   </div>
 </div>
</div>
<br/>
<br/>
</body>
</html>
