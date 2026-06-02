<?php
$host="localhost";
$user="root";
$pass="";
$db="Motion_db";
$conn=mysqli_connect($host,$user,$pass,$db);
if($conn){
echo "connection successfully";
}
else{
    echo "connection failed";
}
$data=JSON_decode(file_get_contents("php://input"),true);
if($data){
    $distance=$data["intera"];
    $insert=mysqli_query($conn,"insert into Motion_data(Motion dectected) values('$distance')");
    if($insert){
        echo "data inserted successfully";
    }
    else{
        echo "failed to fetch data";

    }
    }
    else{
        echo "no data received from esp32";
    }
?>