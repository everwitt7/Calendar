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

$shareUser = (string) $_POST['shareUser'];
$title = (string) $_POST['title'];
$opt = $_POST['opt'];

$stmt = $mysqli->prepare("SELECT flag, eventTitle, eventDateTime FROM events WHERE eventId=?");
if(!$stmt){
	echo json_encode(array("success" => false));
    exit;
}
else{
	$stmt->bind_param('i', $opt);
	$stmt->execute();
	$stmt->bind_result($flag, $eventTitle, $eventDateTime);
	$stmt->fetch();
	$stmt->close();

	$stmt = $mysqli->prepare("INSERT INTO events (flag, eventTitle, eventDateTime, eventUser) VALUES (?, ?, ?, ?)");
	if(!$stmt){
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
	}
	$stmt->bind_param('isss', $flag, $eventTitle, $eventDateTime, $shareUser);
	$stmt->execute();
	$stmt->close();
	echo json_encode(array("success" => true));
	exit;
}
?>