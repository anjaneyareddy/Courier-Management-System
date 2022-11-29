<?php
	include_once('connection.php');

	$output = array('error' => false);

	$database = new Connection();
	$db = $database->open();

	try{
        extract($_POST);
		$stmt = $db->prepare("SELECT * FROM parcels where reference_number = :ref_no");
		$stmt->bindParam(':ref_no', $ref_no);
		$result =$stmt->execute();
        if($stmt->rowCount()<=0){
            $data =2;
        }
        else{
            $parcel = $stmt->fetch();
			$data[] = array('status'=>'Item accepted by Courier','date_created'=>date("M d, Y h:i A",strtotime($parcel['date_created'])));
			$history = $db->prepare("SELECT * FROM parcel_tracks where parcel_id = :parcel_id");
            $history->bindParam(':parcel_id', $parcel['id']);
            $history->execute();
			$status_arr = array("Item Accepted by Courier","Collected","Shipped","In-Transit","Arrived At Destination","Out for Delivery","Ready to Pickup","Delivered","Picked-up","Unsuccessfull Delivery Attempt");
           
			while($row = $history->fetch()){
				$row['date_created'] = date("M d, Y h:i A",strtotime($row['date_created']));
				$row['status'] = $status_arr[$row['status']];
				$data[] = $row;
			}
        }
	}
	catch(PDOException $e){
		
        $data=2;
	}

	//close connection
	$database->close();

	echo json_encode($data);

?>