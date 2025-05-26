<?php

$host="localhost:3307";
$user="root";
$pass="";
$db="customer";

//database connection
$conn=new mysqli($host,$user,$pass,$db);

//check connection
if(!$conn){
    header("Location: ../errors/db.php");
    die();
}
