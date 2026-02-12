<?php
$conn = new mysqli("localhost","root","","demo") ;

if(!$conn){
    die("Connection Failed" . mysqli_connect_error());
}

?>