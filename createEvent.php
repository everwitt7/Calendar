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

$username = (string) $_POST['username'];
$title = (string) $_POST['title'];
$datetime = (string) $_POST['datetime'];
//$time = (string) $_POST['time'];
$event = null;

$stmt = $mysqli->prepare("INSERT INTO events (eventTitle, eventDateTime, eventUser) VALUES (?, ?, ?)");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('sss', $title, $datetime, $username);
$stmt->execute();
$stmt->close();
echo json_encode(array("success" => true));
exit;
?>