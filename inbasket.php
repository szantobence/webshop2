<?php

 $_SESSION['inbasket'] = basketContent();

function basketContent() {

  global $conn;

    if(isset($_SESSION['in'])) {

      $owner = $_SESSION['userid'];

    } else {

      $owner = session_id();

    }

  $query = "SELECT * FROM basket WHERE KosarTulajdonosa = '$owner'";
  $sql3 = mysqli_query($conn, $query);
  $rows = mysqli_num_rows($sql3);

  $_SESSION['inbasket'] = $rows;

  return $rows;

}  

?>