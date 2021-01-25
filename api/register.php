<?php
include ('../api/config/database.php');

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$firstName = '';
$lastName = '';
$email = '';
$password = '';

$data = json_decode(file_get_contents("php://input"));
$firstName = $data->first_name;
$lastName = $data->last_name;
$email = $data->email;
$password = $data->password;
$password_hash = password_hash($password, PASSWORD_BCRYPT);
$sql = "insert into users(first_name, last_name, email, password) values('$firstName', '$lastName', '$email', '$password_hash')";
mysqli_query($cn, $sql);
http_response_code(200);
echo json_encode(array("message" => "User was successfully registered."));
?>