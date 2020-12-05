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

$item = array();
$qty = array();
$sql = "";
if (isset($_POST['dataarray'])){
$data = json_decode($_POST["dataarray"], false);
	foreach ((array)$data as $key =>$value) {

			$file =  mysqli_real_escape_string($conn,$value->file);
			$title =  mysqli_real_escape_string($conn,$value->title);
			$desc =  mysqli_real_escape_string($conn,$value->desc);
			$qty =  mysqli_real_escape_string($conn,$value->qty);
			$price =  mysqli_real_escape_string($conn,$value->price);
			$date =  mysqli_real_escape_string($conn,$value->date);
if (($value->file=="") OR ($value->title=="") OR ($value->desc=="") OR ($value->qty=="") OR ($value->price=="") OR ($value->date=="")){
				$response->code = 0;
				$response->message = "Please Fill All Fields.";
				$responseJSON = json_encode($response);
				echo $responseJSON;
				exit();
}
$sql .= "INSERT INTO `items` (`id`, `file`, `title`,`desc`, `qty`,`price`, `date`) VALUES (NULL, '$file', '$title', '$desc', '$qty', '$price', '$date');";

	}

	if($conn->multi_query($sql)){
		$response->code = 1;
		$response->message = "Saved Successfully.";
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

} else {
	$response->code = 0;
	$response->message = "Please fill all fields.";
	$responseJSON = json_encode($response);
	echo $responseJSON;
	exit();

}






?>