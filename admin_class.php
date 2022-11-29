<?php
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
ini_set('display_errors', 0);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		// print_r($_POST);die;
			$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where email = '".$email."' and password = '".md5($password)."'  ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			// if($_POST["remember_me"]=='1' || $_POST["remember_me"]=='on')
            // {
            // 	$hour = time() + 3600 * 24 * 30;
            //     setcookie('email', $email, $hour);
            //     setcookie('password', $password, $hour);
            // }
				return 1;
		}else{
			return 2;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		// header("location:login.php");
		header("location:landingpage.php");
	}
	function login2(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '".$student_code."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['rs_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		
		if($_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.str_replace(' ', '_', $_FILES['img']['name']);
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			foreach ($_POST as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_FILES['img']['tmp_name'] != '')
			$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}
	function signup(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass')) && !is_numeric($k)){
				if($k =='password'){
					if(empty($v))
						continue;
					$v = md5($v);

				}
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");

		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if(!in_array($key, array('id','cpass','password')) && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
					$_SESSION['login_id'] = $id;
			return 1;
		}
	}

	function update_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','table')) && !is_numeric($k)){
				
				if($k =='password')
					$v = md5($v);
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		
		if($_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			foreach ($_POST as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_FILES['img']['tmp_name'] != '')
			$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_system_settings(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k => $v){
			if(!is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if($_FILES['cover']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set $data where id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if($save){
			foreach($_POST as $k => $v){
				if(!is_numeric($k)){
					$_SESSION['system'][$k] = $v;
				}
			}
			if($_FILES['cover']['tmp_name'] != ''){
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image(){
		extract($_FILES['file']);
		if(!empty($tmp_name)){
			$fname = strtotime(date("Y-m-d H:i"))."_".(str_replace(" ","-",$name));
			$move = move_uploaded_file($tmp_name,'../assets/uploads/'. $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path =explode('/',$_SERVER['PHP_SELF']);
			$currentPath = '/'.$path[1]; 
			if($move){
				return $protocol.'://'.$hostName.$currentPath.'/assets/uploads/'.$fname;
			}
		}
	}
	function save_branch(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(empty($id)){
			$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$i = 0;
			while($i == 0){
				$bcode = substr(str_shuffle($chars), 0, 15);
				$chk = $this->db->query("SELECT * FROM branches where branch_code = '$bcode'")->num_rows;
				if($chk <= 0){
					$i = 1;
				}
			}
			$data .= ", branch_code='$bcode' ";
			$save = $this->db->query("INSERT INTO branches set $data");
		}else{
			$save = $this->db->query("UPDATE branches set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_branch(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM branches where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_parcel(){
		extract($_POST);
		foreach($price as $k => $v){
			$data = "";
			foreach($_POST as $key => $val){
				if(!in_array($key, array('id','weight','height','width','length','price')) && !is_numeric($key)){
					if(empty($data)){
						$data .= " $key='$val' ";
					}else{
						$data .= ", $key='$val' ";
					}
				}
			}
			if(!isset($type)){
				$data .= ", type='2' ";
			}
				$data .= ", height='{$height[$k]}' ";
				$data .= ", width='{$width[$k]}' ";
				$data .= ", length='{$length[$k]}' ";
				$data .= ", weight='{$weight[$k]}' ";
				$price[$k] = str_replace(',', '', $price[$k]);
				$data .= ", price='{$price[$k]}' ";
			if(empty($id)){
				$i = 0;
				while($i == 0){
					$ref = sprintf("%'012d",mt_rand(0, 999999999999));
					$chk = $this->db->query("SELECT * FROM parcels where reference_number = '$ref'")->num_rows;
					if($chk <= 0){
						$i = 1;
					}
				}
				$data .= ", reference_number='$ref' ";
				if($save[] = $this->db->query("INSERT INTO parcels set $data"))
					$ids[]= $this->db->insert_id;
			}else{
				if($save[] = $this->db->query("UPDATE parcels set $data where id = $id"))
					$ids[] = $id;
			}
		}
		if(isset($save) && isset($ids)){
			// return json_encode(array('ids'=>$ids,'status'=>1));
			return 1;
		}
	}
	function save_complaint(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(in_array($k, array('parcel_number','message')) && !is_numeric($k)){
				
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$created_by =$_SESSION['login_id'];
		$created_date = date("Y-m-d H:i:s");
		$data .= ", created_by='$created_by' ";
		$data .= ", created_date='$created_date' ";
		$check = $this->db->query("SELECT * FROM parcels where reference_number ='$parcel_number' ")->num_rows;
		
		if($check <= 0){
			return 2;
			exit;
		}else{
			if(empty($id)){
				$save = $this->db->query("INSERT INTO complaints set $data");

			}else{
				$save = $this->db->query("UPDATE complaints set $data where id = $id");
			}

		}
		
		if($save){
			return 1;
		}
		
		// if(isset($save) && isset($ids)){
		// 	// return json_encode(array('ids'=>$ids,'status'=>1));
		// 	return 1;
		// }
	}

	function update_complaint(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(in_array($k, array('id','parcel_number','message','status')) && !is_numeric($k)){
				
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$created_by =$_SESSION['login_id'];
		$created_date = date("Y-m-d H:i:s");
		$data .= ", created_by='$created_by' ";
		$data .= ", created_date='$created_date' ";
		$check = $this->db->query("SELECT * FROM parcels where reference_number ='$parcel_number' ")->num_rows;
		
		if($check <= 0){
			return 2;
			exit;
		}else{
			// if(empty($id)){
			// 	$save = $this->db->query("INSERT INTO complaints set $data");

			// }else{
				$save = $this->db->query("UPDATE complaints set $data where id = $id");
			// }

		}
		
		if($save){
			return 1;
		}
		
		
	}
	function delete_parcel(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM parcels where id = $id");
		if($delete){
			return 1;
		}
	}
	function update_parcel(){
		extract($_POST);
		$update = $this->db->query("UPDATE parcels set status= $status where id = $id");
		$save = $this->db->query("INSERT INTO parcel_tracks set status= $status , parcel_id = $id");
		if($update && $save)
			return 1;  
	}
	function save_comment(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(in_array($k, array('parcel_number','comment','status')) && !is_numeric($k)){
				
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		// print_r($data);die;

		$created_by =$_SESSION['login_id'];
		$created_date = date("Y-m-d H:i:s");
		$data .= ", user_id='$created_by' ";
		$data .= ", created_at='$created_date' ";
		$data .= ", comment_type='replay' ";
		$check = $this->db->query("SELECT * FROM parcels where reference_number ='$parcel_number' ")->num_rows;
		$parcel_number =$_POST['parcel_number'];
		$status =$_POST['status'];
		if($check <= 0){
			return 2;
			exit;
		}else{

			// if(empty($id)){
				$save = $this->db->query("INSERT INTO comments set $data");
				$save1 = $this->db->query("UPDATE complaints set status=$status where parcel_number = $parcel_number");

			// }else{
			// 	$save = $this->db->query("UPDATE comments set $data where id = $id");
			// }

		}
		
		if($save){
			return 1;
		}
		
	}
	function get_parcel_history(){
		extract($_POST);
		$data = array();
		$parcel = $this->db->query("SELECT * FROM parcels where reference_number = '$ref_no'");
		if($parcel->num_rows <=0){
			return 2;
		}else{
			$parcel = $parcel->fetch_array();
			$data[] = array('status'=>'Item accepted by Courier','date_created'=>date("M d, Y h:i A",strtotime($parcel['date_created'])));
			$history = $this->db->query("SELECT * FROM parcel_tracks where parcel_id = {$parcel['id']}");
			$status_arr = array("Item Accepted by Courier","Collected","Shipped","In-Transit","Arrived At Destination","Out for Delivery","Ready to Pickup","Delivered","Picked-up","Unsuccessfull Delivery Attempt");
			while($row = $history->fetch_assoc()){
				$row['date_created'] = date("M d, Y h:i A",strtotime($row['date_created']));
				$row['status'] = $status_arr[$row['status']];
				$data[] = $row;
			}
			return json_encode($data);
		}
	}
	function get_complaint_history(){
		extract($_POST);
		$data = array();
		// $comments = $this->db->query("SELECT * FROM comments where parcel_number = '".$_POST['ref_no']."'");
		$comments = $this->db->query("SELECT * FROM comments");
		
		if($comments->num_rows <=0){
			return 2;
		}else{
			$data[] = array();
			while($row = $comments->fetch_assoc()){
				$row['created_at'] = date("M d, Y h:i A",strtotime($row['created_at']));
				
				$row['user'] = $this->get_userdetails($row['user_id']);
				$row['avatar']=strtoupper(substr($row['user']['firstname'], 0,1).substr($row['user']['lastname'], 0,1));
				$data[] = $row;
			}
			return json_encode($data);
		}
	}
	function get_report(){
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT * FROM parcels where date(date_created) BETWEEN '$date_from' and '$date_to' ".($status != 'all' ? " and status = $status " : "")." order by unix_timestamp(date_created) asc");
		$status_arr = array("Item Accepted by Courier","Collected","Shipped","In-Transit","Arrived At Destination","Out for Delivery","Ready to Pickup","Delivered","Picked-up","Unsuccessfull Delivery Attempt");
		while($row=$get->fetch_assoc()){
			$row['sender_name'] = ucwords($row['sender_name']);
			$row['recipient_name'] = ucwords($row['recipient_name']);
			$row['date_created'] = date("M d, Y",strtotime($row['date_created']));
			$row['status'] = $status_arr[$row['status']];
			$row['price'] = number_format($row['price'],2);
			$data[] = $row;
		}
		return json_encode($data);
	}
	function get_complaint_report(){
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT * FROM complaints");
		// $status_arr = array("Item Accepted by Courier","Collected","Shipped","In-Transit","Arrived At Destination","Out for Delivery","Ready to Pickup","Delivered","Picked-up","Unsuccessfull Delivery Attempt");
		while($row=$get->fetch_assoc()){
			$row['parcel_number'] = ucwords($row['parcel_number']);
			$row['message'] = ucwords($row['message']);
			$row['date_created'] = date("M d, Y",strtotime($row['created_date']));
			// $row['status'] = $status_arr[$row['status']];
			$row['user'] = $this->get_userdetails($row['created_by']);
			$data[] = $row;
		}
		return json_encode($data);
	}

	function get_userdetails($user_id){
		
		$data = array();
		$get = $this->db->query("SELECT * FROM users where id=$user_id");
		while($row=$get->fetch_assoc()){
			$row['firstname'] = ucwords($row['firstname']);
			$row['lastname'] = ucwords($row['lastname']);
			$row['email'] = ucwords($row['email']);
			$row['type'] = $row['type'];
			$row['branchid'] = $row['branch_id'];
			$row['user_name'] =ucwords($row['firstname'].' '.$row['middlename'].' '.$row['lastname']);
			$data = $row;
		}
		return $data;
	}

	function delete_complaint(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM complaints where id = $id");
		if($delete){
			return 1;
		}
	}

}