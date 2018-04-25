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
$event = null;

$stmt = $mysqli->prepare("UPDATE events SET flag = !flag WHERE eventId = ?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('i', $opt);
$stmt->execute();
$stmt->close();
echo json_encode(array("success" => true));
exit;
?>