<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM complaints where  id= ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
$action='update_complaint';
include 'new_complaint.php';


?>