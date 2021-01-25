<?php
ini_set("display_errors",1);

include_once './config/database.php';
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$secret_key = "YOUR_SECRET_KEY";
$jwt = null;




if($_SERVER['REQUEST_METHOD'] === "POST"){

    // $data = json_decode(file_get_contents("php://input"));
    // $jwt = ($data->jwt);

    $data = getallheaders();
    $jwt = $data['Authorization'];

    if($jwt){

        try{
            $jwt = str_replace("Bearer", "", $jwt);
            $jwt = JWT::encode("", $secret_key, 'HS256');

            $decoded = JWT::decode($jwt, $secret_key, array('HS256'));

            http_response_code(200);
            //$user_id = $decoded->data->id;

            // echo json_encode(array(
            //     "status" => 1,
            //     "message" => "we got token",
            //     "user_data" => $decoded,
            //     "user_id" => $user_id
            // ));

            $databaseService = new DatabaseService();
            $conn = $databaseService->getConnection();

            $query = "insert into student_profile(sname,mobile) values(?,?)";
            $stmt = $conn->prepare($query);
            $sdata = json_decode(file_get_contents("php://input"));
            $sname = $sdata->sname;
            $mobile = $sdata->mobile;
            if($stmt->execute([$sname,$mobile])){
              http_response_code(200);
              echo json_encode(array(
                "status" => 1,
                "message" => "student created"
            ));
          }else{
              http_response_code(500);
              echo json_encode(array(
                "status" => 1,
                "message" => "Failed tp create student"
            ));
          }


      }catch (Exception $e){

        http_response_code(500);
        echo json_encode(array(
            "status" => 0,
            "error" => $e->getMessage()
        ));
    }
}
}else{
    echo "access denied";
}
?>