<?php
ini_set('display_errors', 1);
Class Htmltags {
	// private $db;

	// public function __construct() {
	// 	ob_start();
   	// // include 'db_connect.php';
    
    // // $this->db = $conn;
	// }
	// function __destruct() {
	//     // $this->db->close();
	//     ob_end_flush();
	// }

	function badge($value,$bgColor="secondary",$name="" , $class="",$id="",$attributes=""){
        echo "<span class='badge badge-".$bgColor.$class."' id='".$id."' name='".$name."' ".$attributes." > ".$value."</span>";
    }
	function badge_pills($value,$bgColor="secondary",$name="",$class="",$id="",$attributes=""){
        echo "<span class='badge badge-pill badge-".$bgColor.$class."' id='".$id."' name='".$name."' ".$attributes." > ".$value."</span>";
    }

}

?>