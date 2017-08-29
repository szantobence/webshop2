<?php

$host = "localhost";
$dbase = "webshop";
$user = "root";
$pass = "";

$conn = mysqli_connect($host, $user, $pass, $dbase);
mysqli_query($conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
if (!$conn) {

       die ("SQL connect error, call system administrator!");

}
