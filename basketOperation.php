<?php

$query = "SELECT * FROM cikktorzs WHERE cikkcsoportkod = $b";
  $sql = mysqli_query($conn, $query);
  $quantity = filter_input(INPUT_POST, 'quantity');

  $successSign = quantityAndItemInBasketCheck($quantity);

  header('Refresh:0; url=index.php?a=shop&b='.$b.'&c='.$c.'&f='.$f.'&e='.'1'.'&dzs='.$quantity.'&h='.$successSign.'');

 function quantityAndItemInBasketCheck($quantity) {
    global $conn;
    global $b;
    global $c;
    global $f;
    global $successSign;

    if(isset($_SESSION['userid'])) {

    $userid = $_SESSION['userid'];

    } else {

      $userid = session_id();

    }

    $query = "SELECT * FROM basket WHERE KosarTulajdonosa = '$userid' AND KosarCikkszamTartalma = '$f'";
    $sql = mysqli_query($conn, $query);
    $numrows = mysqli_num_rows($sql);
   // echo $numrows;

   $query2 = "SELECT * FROM cikktorzs WHERE cikkszam = '$f'";
   $sql2 = mysqli_query($conn, $query2);
   $row3 = mysqli_fetch_assoc($sql2);

  //  if($quantity > $row3['Darab']) {

  //   $quantity = $row3['Darab'];

  //   echo '<div class="alert alert-dismissible alert-warning">
  //         <button type="button" class="close" data-dismiss="alert">&times;</button>
  //         <p align="center">Nincs elegendo termek raktaron, ezert a maximalisat helyezzuk a kosarba!</p>
  //         </div>';

  //  }
    if($quantity > $row3['Darab']) {

      $quantity = $row3['Darab'];

    }

    if(mysqli_num_rows($sql) >= 1 && $quantity > 0) {

      $query = "UPDATE basket SET Darabszam = '$quantity' WHERE KosarTulajdonosa = '$userid' AND KosarCikkszamtartalma = '$f'";
      mysqli_query($conn, $query);

      $successSign = 1;

    } elseif(mysqli_num_rows($sql) == 0 && isset($f) && $quantity > 0) {

      $kuponpercent = $row3['Kuponkodszazalek'];
      $kupon = $row3['kuponkod'];
      $kuponkezd = $row3['Kuponkezdes'];
      $kuponvege = $row3['Kuponvege'];
      $ar = $row3['kiskerar'];


      $query = "INSERT INTO basket (KosarTulajdonosa, KosarCikkszamTartalma, Darabszam,termekar, kuponkod, kuponszazalek, kuponkezd, kuponveg) VALUES ( '$userid', '$f', '$quantity','$ar', '$kupon', '$kuponpercent', '$kuponkezd', '$kuponvege')";
      mysqli_query($conn, $query);

      $successSign = 1;

    } elseif($quantity <= 0) {

      echo '  <script type="text/javascript">
					alert ("Csak pozitív számot tehetsz a kosárba !");
					window.open ("index.php?a=shop&b='.$b.'", "_self");
 					</script> ';

    }

    return $successSign;

    // if($successSign == 1 ) {
    //    echo '<div class="alert alert-dismissible alert-success", align="center">
    //       <button type="button" class="close" data-dismiss="alert">&times;</button>
    //       <strong>'.$quantity.' darab termek kerult a kosarba!</strong>
    //       </div>';
    // }
  }

?>