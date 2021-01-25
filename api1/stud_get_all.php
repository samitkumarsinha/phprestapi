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




if($_SERVER['REQUEST_METHOD'] === "GET"){

    // $data = json_decode(file_get_contents("php://input"));
    // $jwt = ($data->jwt);

    $data = getallheaders();
    $jwt = $data['Authorization'];

    if($jwt){

        try{

          
            $jwt = str_replace("Bearer", "", $jwt);
           
            $data =[
                "id"=>"1","firstname"=>"samit","lastname"=>"sinha","email"=>"samit@gmail.com"
            ];
            
            $jwt = JWT::encode($data, $secret_key, 'HS256');

            $decoded = JWT::decode($jwt, $secret_key, array('HS256'));

            http_response_code(200);
            //print_r($decoded);
            // $user_id = $decoded->data->id;

            // echo json_encode(array(
            //     "status" => 1,
            //     "message" => "we got token",
            //     "user_data" => $decoded,
            //     "user_id" => $user_id
            // ));

            $databaseService = new DatabaseService();
            $conn = $databaseService->getConnection();

            $query = "select * from student_profile";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $arr[]=array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $arr[] = $row;
          }
          print_r($arr);
         

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