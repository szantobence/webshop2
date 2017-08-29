<?php

detailsQuery();

function detailsQuery() {
  global $e;
  global $conn;
  global $b;
  $filename = '';

  //  if($e == '1'){

  //   $quantity = $dzs;
  //   basketIndicator($quantity);

  // }

  $query = "SELECT * FROM cikktorzs WHERE ID = $e";
  $sql = mysqli_query($conn, $query);

  $row = mysqli_fetch_assoc($sql);

  $picture = 'pictures/'.$row['cikkszam'].'_1.'.'jpg';

  $productQuantity = productInBasketCounter($row['cikkszam']);

  echo'
  <a href="index.php?a=shop&b='.$b.'"><i class="fa fa-arrow-left fa-3x" aria-hidden="true"></i></a>
  ';

  echo'<table class="table table-striped table-hover">
   <thead>
      <tr>
          <th style="text-align: center; vertical-align: middle;"><a href="'.$picture.'" data-lightbox="haha"><img src="'.$picture.'" alt="Mountain View" style="width:500px;" ></a></th>
        </tr>
   </thead>
  </table>';


  echo'
    <table class="table table-striped table-hover">
    <thead>
        <tr>
          <th width="20%">Megnevezes</th>
          <th width="20%">Cikkszam</th>
          <th width="20%">Ar</th>
          <th width="20%">Leiras</th>
        </tr>
      </thead>
      <tbody>
      <fieldset>
        <tr>
          <form class="form-horizontal" action="index.php?a=basketOperation&c='.$row['ID'].'&f='.$row['cikkszam'].'" method="post"> 
          <td width="14%">'.$row['megnevzes'].'</a></td>
          <td width="14%">'.'('.$row['cikkszam'].')'.'</td>
          <td width="14%">'.$row['kiskerar'].'</td>
          <td width="14%">'.$row['informacio'].'</td>'; 
    if($productQuantity > 0) {       
      echo  '<td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">Already in basket:'.$productQuantity.'</td>';
    } else { 
      echo  '<td width="14%"></td>';    
    }
      echo  '<td width="14%"><div class="form-group" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">
                          <input class="form-control input-sm" type="text" id="inputSmall" name="quantity" value="1">
                          </div>
                          <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                              <input type="submit" class="btn btn-primary" name="submit" value="To basket">
                            </div>
                          </div>
                          </td>
          </fieldset>
          </form>
        </tr>
      </tbody>
    </table>
    ';
    echo '<table class="table table-striped table-hover">
           <thead>
                <tr>';
    for($i = 0; $i < 4;$i++) {

    $filename = 'pictures/'.$row['cikkszam'].'_'.$i.'.'.'jpg';

    if(file_exists($filename)) {

      echo'
          <th style="text-align: center; vertical-align: middle;"><a href="'.$filename.'" data-lightbox="'.$row['cikkszam'].'"><img src="'.$filename.'" alt="Mountain View" style="width:300px;" ></a></th>
          ';

    }
  }
  echo '</tr>
           </thead>
           </table>';
    // echo'
    //   <table class="table table-striped table-hover">
    //   <thead>
    //     <tr>
    //       <th>Nev</th>
    //     </tr>
    //   </thead>
    //   <tbody>
    //     <tr>
    //       <form class="form-horizontal" action="index.php?a=shop&c='.$row['ID'].'&f='.$row['cikkszam'].'" method="post">
    //       <fieldset>
    //       <td><a href="'.$picture.'"><img src="'.$picture.'" alt="Mountain View" style="width:240px;"></a></td>
    //       </fieldset>
    //       </form>
    //     </tr>

    //   </tbody>
    // </table>
    // ';

}

 function basketIndicator($quantity) {
    global $conn;
    global $b;
    global $c;
    global $f;
    global $e;
    global $h;

    echo $quantity;
    echo 'BELEPTEM A FUNCTION-BE !';

     $userid = $_SESSION['userid'];

    $query = "SELECT * FROM basket WHERE KosarTulajdonosa = '$userid' AND KosarCikkszamTartalma = '$f'";
    $sql = mysqli_query($conn, $query);
    $numrows = mysqli_num_rows($sql);
   // echo $numrows;

   $query2 = "SELECT * FROM cikktorzs WHERE cikkszam = '$f'";
   $sql2 = mysqli_query($conn, $query2);
   $row3 = mysqli_fetch_assoc($sql2);

   if($quantity > $row3['Darab']) {

    $quantity = $row3['Darab'];

    echo '<div class="alert alert-dismissible alert-warning">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <p align="center">Nincs elegendo termek raktaron, ezert a maximalisat helyezzuk a kosarba!</p>
          </div>';
   }

     if($h == '1' ) {
       echo '<div class="alert alert-dismissible alert-success", align="center">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>'.$quantity.' darab termek kerult a kosarba!</strong>
          </div>';
    }


  }

  function quantityAndItemInBasketCheck($quantity) {
    global $conn;
    global $b;
    global $c;
    global $f;
    global $successSign;

    $userid = $_SESSION['userid'];

    $query = "SELECT * FROM basket WHERE KosarTulajdonosa = '$userid' AND KosarCikkszamTartalma = '$f'";
    $sql = mysqli_query($conn, $query);
    $numrows = mysqli_num_rows($sql);
   // echo $numrows;

   $query2 = "SELECT * FROM cikktorzs WHERE cikkszam = '$f'";
   $sql2 = mysqli_query($conn, $query2);
   $row3 = mysqli_fetch_assoc($sql2);

   if($quantity > $row3['Darab']) {

    $quantity = $row3['Darab'];

    echo '<div class="alert alert-dismissible alert-warning">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <p align="center">Nincs elegendo termek raktaron, ezert a maximalisat helyezzuk a kosarba!</p>
          </div>';

   }

    if(mysqli_num_rows($sql) >= 1 && $quantity > 0) {

      $query = "UPDATE basket SET Darabszam = '$quantity' WHERE KosarTulajdonosa = '$userid' AND KosarCikkszamtartalma = '$f'";
      mysqli_query($conn, $query);

      $successSign = 1;

    } elseif(mysqli_num_rows($sql) == 0 && isset($f) && $quantity > 0) {

      $query = "INSERT INTO basket (KosarTulajdonosa, KosarCikkszamTartalma, Darabszam) VALUES ( '$userid', '$f', '$quantity')";
      mysqli_query($conn, $query);

      $successSign = 1;

    } elseif($quantity <= 0) {

      echo '  <script type="text/javascript">
					alert ("Csak pozitív számot tehetsz a kosárba !");
					window.open ("index.php?a=shop&b='.$b.'", "_self");
 					</script> ';

    }

    if($successSign == 1 ) {
       echo '<div class="alert alert-dismissible alert-success", align="center">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>'.$quantity.' darab termek kerult a kosarba!</strong>
          </div>';
    }

  }

  function productInBasketCounter($f) {
    global $conn;
    $userid = $_SESSION['userid'];

    if(isset($_SESSION['in'])) {

      $query = "SELECT Darabszam FROM basket WHERE KosarTulajdonosa = '$userid' AND KosarCikkszamTartalma = '$f'";
      $sql = mysqli_query($conn, $query);

      $row = mysqli_fetch_assoc($sql);

      return $row['Darabszam'];

    }
  }

?>