<?php
try{
$pdo = new PDO('mysql:host=localhost;dbname=easyeglp_prueba','easyeglp_prueba','Ld_HkcckQm3j1#@.Kl2');
//echo 'conectado';
} catch (PDOException $e){
print "¡Error!: " . $e->getMessage() . "<br/>";
die();
}
$servername = "localhost";
$dBusername = "easyeglp_prueba";
$dBpassword = "Ld_HkcckQm3j1#@.Kl2";
$dBname = "easyeglp_prueba";
$conn = mysqli_connect($servername, $dBusername, $dBpassword, $dBname);
if(!$conn) {
die("Connection To Database Failed: ".mysql_connect_error());
}
?>