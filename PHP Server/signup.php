<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $data = json_decode(file_get_contents("php://input"));
    $name = $data->name;
    $pass = $data->password;

    $servername = "localhost";
    $username = "root";
    $password = "jamshaid19gh";
    $dbname = "authentication_db";
    $port = "3306";

    $conn = new mysqli($servername , $username , $password , $dbname , $port);
    if($conn->connect_error)
    {
        echo "[error]Failed to connect : " . $conn->connect_error;
        die("[error]Failed to connect : " . $conn->connect_error);
    }
    $current_time  = date_timestamp_get(date_create());
    $sql = "DELETE FROM `auth_token_table` WHERE  $current_time >=  expiry_date ";
    $conn->query($sql);
    $sql = "INSERT INTO users (name , password) VALUES ('$name' , '$pass')";
    if($conn->query($sql))
    {
        $last_id = $conn->insert_id;
        $date_created = date_timestamp_get(date_create());
        $expiry_date = $date_created + 15; //+86400;
        $authToken = bin2hex(random_bytes(16)) . '-' . $expiry_date ;
        $sql = "INSERT INTO `auth_token_table`(`auth_token`, `date_created`, `expiry_date`, `id`) VALUES ('$authToken' , '$date_created' , '$expiry_date' , '$last_id')";
        if($conn->query($sql)){
            echo $authToken ;
        }
        else{
            echo "[error]Failed to generate AuthToken : " . $conn->error;
        }
        
    }
    else{
        echo "[error]Failed to insert data : " . $conn->error;
    }


}

?>
