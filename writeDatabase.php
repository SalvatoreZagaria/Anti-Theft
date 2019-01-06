<?php
$servername = "localhost";
$username = "root";
$password = "emidio";
$dbname = "alarmSystem";

$uid = $_GET['uid'];
$token = $_GET['token'];
$off = $_GET['off'];
$sensor = $_GET['sensor'];

$zero = "0";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//First check
$check = "SELECT COUNT(*) FROM data WHERE uid='{$uid}'";
$result = mysqli_query($conn,$check);
$row_for_1_check=mysqli_fetch_row($result);
printf("%s \n", $row_for_1_check[0]);

$num_rows = (string)$row_for_1_check[0];
settype($zero, "string");
settype($sensor, "string");

if($sensor=='0'){
	if($num_rows==$zero){
		$sql = "INSERT INTO data (uid, token, off) VALUES 	('$uid', '$token', '$off')";
	}else{
		$sql = "UPDATE data SET token = '{$token}' WHERE uid 	= '{$uid}'";
	}
}else{
	$sql = "UPDATE data SET off='{$off}' WHERE uid = '{$uid}'";
}

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>