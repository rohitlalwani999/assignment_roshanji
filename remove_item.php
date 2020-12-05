<?php
$response = new \stdClass();
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli("localhost","root","");
$conn->select_db("assignment");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

date_default_timezone_set('Asia/Kolkata');

$sql = "";
if (isset($_POST['id'])){
	$id =  mysqli_real_escape_string($conn,$_POST['id']);
	$sql = "DELETE FROM items WHERE id='$id'";


	if($conn->query($sql)){
		$response->code = 1;
		$response->message = "Removed Successfully.";
		$responseJSON = json_encode($response);
		echo $responseJSON;
		exit();
	} else {
		$response->code = 0;
		$response->message = "Have Some Error.";
		$responseJSON = json_encode($response);
		echo $responseJSON;
		exit();
	}

} else { // No id send.
	$response->code = 0;
	$response->message = "Please fill all fields.";
	$responseJSON = json_encode($response);
	echo $responseJSON;
	exit();

}

?>