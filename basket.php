<?php
if(isset($_SESSION['in'])) {

  basketWhenLogin();

} else { 

  basketWhenNotLogin();

}

function basketWhenLogin() {
  global $conn;
  global $c;
  $id = $c;

  $query = "SELECT Cikkszam FROM Cikktorzs WHERE ID = $id";
  $sql = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($sql);
  $productNum = $row['Cikkszam'];
  $date = date("Y-m-d H:i:s");
  $name = $_SESSION['email'];
  $sessionName = session_id();

  // $query = "INSERT INTO basket ( `KosarCikkszamTartalma`, `KosarTulajdonosa`, `Darabszam`, `Datum`) VALUES ('$productNum', '$name' , 1, '$date')";
  // mysqli_query($conn, $query);

}

function basketWhenNotLogin() {
  global $conn;
  global $c;
  $id = $c; 

  $query = "SELECT Cikkszam FROM Cikktorzs WHERE ID = $id";
  $sql = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($sql);
  $productNum = $row['Cikkszam'];
  $date = date("Y-m-d H:i:s");
  $name = session_id();

  // $query = "INSERT INTO basket ( `KosarCikkszamTartalma`, `KosarTulajdonosa`, `Darabszam`, `Datum`) VALUES ('$productNum', '$name' , 1, '$date')";
  // mysqli_query($conn, $query);

}

?>