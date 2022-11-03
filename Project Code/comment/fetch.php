<?php
session_start();
include('configdb.php');
$column =array("uid","status","bluep","yellowp","redp","scomment");
$query ="SELECT * FROM senloc WHERE status ='OFF' ";
if (!empty($_GET['duid'])){
  $duid=$_GET['duid'];
  $query ="SELECT * FROM senloc WHERE uid='".$duid."' ";

}

//post search
if($_POST["search"]["value"]){
  $query ="SELECT * FROM senloc ";
  if(isset($_POST["search"]["value"]))
  {
    $st="OFF";
    $query .='
WHERE uid  LIKE "%'.$_POST["search"]["value"].'%"
    OR scomment  LIKE "%'.$_POST["search"]["value"].'%"
    ';
      $query .=' OR status = "%'.$st.'%"';
  }
}
if (isset($_POST["order"]))
{
  $query .='ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
/*else
{
  $query .= 'ORDER BY uid DESC';

}*/
$query1='';
if($_POST["length"])
{
  if($_POST["length"]!=-1)
  {
    $query1 .=  'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];

  }
}
$statement =$connect->prepare($query);
$statement->execute();
$number_filter_row=$statement->rowCount();
$statement=$connect->prepare($query . $query1);
$statement->execute();
$result=$statement->fetchALL();
$data = array();
foreach($result as $row)
{
  $sub_array=array();
  $sub_array[]=$row['uid'];
  $sub_array[]=$row['status'];
  $sub_array[]=$row['bluep'];
  $sub_array[]=$row['yellowp'];
  $sub_array[]=$row['redp'];
  $sub_array[]=$row['scomment'];
  $sub_array[]=$row['cus_feed'];
  $data[]=$sub_array;

}
function count_all_data($connect)
{
  $query="SELECT * FROM senloc ";
  $statement=$connect->prepare($query);
  $statement->execute();
  return $statement->rowCount();
}
$output = array(
  'draw' => intval($_POST['draw']),
  'recordsTotal'=>count_all_data($connect),
  'recordsFiltered'=>$number_filter_row,
  'data'=>$data
);
echo json_encode($output);
?>
