<?php
date_default_timezone_set('Africa/Nairobi');
session_start();
include('configdb.php');
$column =array("comme","statu","descript","timestamp");
$query ="SELECT * FROM locs WHERE statu ='OFF' ORDER BY timestamp DESC ";
if (!empty($_GET['duid'])){
  $duid=$_GET['duid'];
  $query ="SELECT * FROM  locs WHERE comme='".$duid."' AND statu='OFF' ORDER BY timestamp DESC ";

}

//post search
if($_POST["search"]["value"]){
  $query ="SELECT * FROM locs ";
  if(isset($_POST["search"]["value"]))
  {
    $st="OFF";
    $query .='
WHERE comme LIKE "%'.$_POST["search"]["value"].'%"
    OR descript  LIKE "%'.$_POST["search"]["value"].'%"
    ';
      $query .=' OR statu = "%'.$st.'%"';
  }
}
if (isset($_POST["order"]))
{$query ="SELECT * FROM locs ";
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
//echo date("h:i:sa");

foreach($result as $row)
{
  $sub_array=array();
  $sub_array[]=$row['comme'];
  $sub_array[]=$row['statu'];
  $sub_array[]=$row['descript'];

  $sub_array[]=$row['timestamp'];
  $data[]=$sub_array;

}
function count_all_data($connect)
{
  $query="SELECT * FROM locs ";
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
function time2string($timeline) {
    $periods = array('day' => 86400, 'hour' => 3600, 'minute' => 60, 'second' => 1);
     $ret="";
    foreach($periods AS $name => $seconds){
        $num = floor($timeline / $seconds);
        $timeline -= ($num * $seconds);
    //if ($timeline<1)
        //break;
        if ($num==0)
        continue;
        $ret .= $num.' '.$name.(($num > 1) ? 's' : ' ').' ';
    }

    return trim($ret);
}
echo json_encode($output);
?>
