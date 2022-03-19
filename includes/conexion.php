p<?php

try{

$pdo = new PDO('mysql:host=localhost;dbname=easycryptos','root','');

//echo 'conectado';

} catch (PDOException $e){

print "¡Error!: " . $e->getMessage() . "<br/>";

die();

}

$servername = "localhost";

$dBusername = "root";

$dBpassword = "";

$dBname = "easycryptos";

$conn = mysqli_connect($servername, $dBusername, $dBpassword, $dBname);

if(!$conn) {

die("Connection To Database Failed: ".mysql_connect_error());

}

?>
