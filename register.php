<?php
require 'database.php';
header("Content-Type: application/json");

$username = (string) $_POST['username'];
$password = (string) $_POST['password'];
$saltedPass = password_hash($password, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("SELECT username FROM users WHERE username = '$username'");
if(!$stmt){
	echo json_encode(array("success" => false, "message" => "user already exists"));
        exit;
}
$stmt->execute();
$stmt->bind_result($result);
$stmt->fetch();
$stmt->close();

if (strcmp($result, $username) == 0) {
    //header("Location: existinguser.php");
} 

else {
    $stmt = $mysqli->prepare("INSERT INTO users (username, hash) VALUES (?, ?)");
    if(!$stmt){
        echo json_encode(array("success" => false, "message" => "Registration Failed"));
        exit;
    } else {
        $stmt->bind_param('ss', $username, $saltedPass);

        $stmt->execute();

        $stmt->close();
        echo json_encode(array(
        "success" => true
        ));
        exit;
    }
}

?>
