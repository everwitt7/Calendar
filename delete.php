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

$opt = $_POST['opt'];

$stmt = $mysqli->prepare("DELETE FROM events WHERE eventId = '$opt'");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	echo json_encode(array("success" => false));
	exit;
}
$stmt->execute();
$stmt->close();

echo json_encode(array("success" => true));
exit;
?>