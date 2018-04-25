<?php
require 'database.php';
header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();

$_POST['token'] = $_SESSION['token'];
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	echo json_encode(array("success" => false));
	die("Request forgery detected");
}

$stmt = $mysqli->prepare("SELECT flag, eventID, eventTitle, YEAR(eventDateTime) as year, MONTH(eventDateTime) as month, DAY(eventDateTime) as day, TIME(eventDateTime) as time FROM events WHERE eventUser=? order by eventDateTime");
if(!$stmt){
	echo json_encode(array("success" => false));
    exit;
}

$stmt->bind_param('s', $_SESSION['username']);
$stmt->execute();
$eventArray = array();
$eventArray[] = array("success" => true);
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
	$eventArray[] = $row;
}

$stmt->close();
echo json_encode($eventArray);
exit;
?>
