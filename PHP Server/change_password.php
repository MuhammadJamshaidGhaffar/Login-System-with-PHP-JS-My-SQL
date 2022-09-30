<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $data = json_decode(file_get_contents("php://input"));
    $pass = $data->password;
    $auth_token = $data->auth_token;

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
    $sql = "SELECT `id`  FROM `auth_token_table` WHERE auth_token = BINARY '$auth_token' AND  $current_time >=  'expiry_date';";
    $result = $conn->query($sql);

    if ( $result->num_rows > 0)
    {
        $id= $result->fetch_assoc()['id'];
        $sql = "UPDATE `users` SET `password`='$pass' WHERE id =$id;";
        if($conn->query($sql))
        {
            echo "Password Updated successfully";   
        }
        else{
        echo "[error]Failed to Update Password: " . $conn->error;
    }
}else{
        echo "Session Timed out" . $conn->error;
    }


}

?>
