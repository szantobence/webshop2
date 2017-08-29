<?php

if(isset($dzs) && $h == 1) {

  successIndicator($dzs);

}

productsQuery();

function productsQuery() {
  global $conn;

  $query = "SELECT * FROM cikktorzs WHERE termekakcio > 0";
  $sql = mysqli_query($conn, $query);

  echo '<table align="center">
            <tbody>
              <tr>
                <td align="center"><font size="6"><strong>Discounts</strong></font></td>
              </tr>
            </tbody>
          </table></br></br>';

  echo '<table class="table table-striped table-hover">
      <thead>
          <tr>
            <th width="21%"></th>
            <th width="13%">Name</th>
            <th width="13%">Cikkszam</th>
            <th width="13%">Price</th>
            <th width="13%">Description</th>
            <th width="13%">Discount Price</th>
            <th width="13%"></th>
          </tr>
        </thead>'; 

  while($row = mysqli_fetch_assoc($sql)) {

    $discount = $row['kiskerar'] - $row['termekakcio'];

    $picture = 'pictures/'.$row['cikkszam'].'_1.'.'jpg';

    if(file_exists($picture)) {

      $pictureExists = 1;

    } else {

      $picture = 'pictures/nopicture.jpg';

    }

    echo'
      <table class="table table-striped table-hover">
        <tbody>
          <tr>
            <form class="form-horizontal" action="index.php?a=basketOperation&c='.$row['ID'].'&f='.$row['cikkszam'].'" method="post">
            <fieldset>
            <td width="13%"><a href="'.$picture.'" data-lightbox="'.$row['cikkszam'].'"><img src="'.$picture.'" alt="Mountain View" style="width:300px;" ></a></td>
            <td width="13%">'.$row['megnevzes'].'</a></td>
            <td width="13%">'.'('.$row['cikkszam'].')'.'</td>
            <td width="13%"><strike>'.number_format($row['kiskerar'],0,',','.').'</strike></td>
            <td width="13%">'.$row['informacio'].'</td>
            <td width="13%">'.number_format($discount,0,',','.').'</td>
            <td width="13%"><div class="form-group" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">
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

   // echo $row['megnevzes'].'</br>'.$discount.'</br>';

  }
}

function successIndicator($quantity) {
    global $conn;
    global $b;
    global $c;
    global $f;
    global $dzs;
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

?>