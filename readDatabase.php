<?php
$servername = "localhost";
$username = "root";
$password = "emidio";
$dbname = "alarmSystem";

$off = "0";

$uid = $_GET['uid'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT off FROM data";
$result = mysqli_query($conn,$query);
if(!$result){
	echo "2";
}else{
	while ($row = mysqli_fetch_row($result)) {
        
    	if($row[0]=='1'){
		$off = "1";
		break;
		}
	}
	echo $off;
	$off = "0";
}

$query = "SELECT off FROM data WHERE uid = '{$uid}'";
$result = mysqli_query($conn,$query);
if(!$result){
	echo "2";
}else{
	$row = mysqli_fetch_row($result);
       
	if($row[0]=='1'){
		echo "1";
	}else{
		echo "0";
	}
}
?>
