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

$title = (string) $_POST['title'];
$datetime = (string) $_POST['datetime'];
$opt = $_POST['opt'];
$event = null;

$stmt = $mysqli->prepare("UPDATE events SET eventTitle = ?, eventDateTime = ? WHERE eventId = ?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('ssi', $title, $datetime, $opt);
$stmt->execute();
$stmt->close();
echo json_encode(array("success" => true));
exit;
?>