<?php
//action.php
include('configdb.php');
if($_POST['action']=='edit')
{
  $data =array(
    ':uid'=>$_POST['uid'],
    ':status'=>$_POST['status'],
    ':bluep'=>$_POST['bluep'],
    ':yellowp'=>$_POST['yellowp'],
    ':redp'=>$_POST['redp'],
    ':scomment'=>$_POST['scomment'],
    ':cus_feed'=>$_POST['cus_feed']

  );
  $query="
  UPDATE senloc
  SET uid = :uid,
  status = :status,
  bluep= :bluep,
  yellowp= :yellowp,
  redp= :redp,
  scomment= :scomment,
  cus_feed= :cus_feed
   WHERE uid = :uid
  ";
  $statement =$connect->prepare($query);
  $statement ->execute($data);
  echo json_encode($_POST);
}
if($_POST['action'] == 'delete')
{
 $query = "
 DELETE FROM senloc
 WHERE uid = '".$_POST["uid"]."'
 ";
 $statement = $connect->prepare($query);
 $statement->execute();
 echo json_encode($_POST);
}
?>
