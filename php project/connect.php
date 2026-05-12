<?php
$hostname='localhost';
$username='root';
$password='';
$database='project';

$con=mysqli_connect($hostname,$username,$password,$database);

if(!$con){
    die(mysqli_error($con));
}

?>