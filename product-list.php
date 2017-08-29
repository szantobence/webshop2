<?php

  if($b == ''){

    $quantity = $dzs;
    $successSign = 1;

    header('Refresh:0; url=index.php?a=&b='.$b.'&c='.$c.'&f='.$f.'&e='.'1'.'&dzs='.$quantity.'&h='.$successSign.'');

  } else {

    $query = "SELECT cikktorzs.*, cikkcsoportkod.*, cikktorzs.ID AS idka FROM cikktorzs INNER JOIN cikkcsoportkod ON cikkcsoportkod.ID = cikktorzs.cikkcsoportkod WHERE cikktorzs.cikkcsoportkod = $b";

  }

  $sql = mysqli_query($conn, $query);
  $quantity = filter_input(INPUT_POST, 'quantity');
  $picture = '';
  $groupsAlreadyPrint = '';

  // echo $quantity;
  // echo $c;
  // echo $b;
  // echo $_SESSION['userid'];
  // echo $f;

  if($e == '1'){

    $quantity = $dzs;
    basketIndicator($quantity);

  }

// if(filter_input(INPUT_POST, 'submit')) {
//   quantityAndItemInBasketCheck($quantity);
// }
if($b != '') {
  while($rows = mysqli_fetch_assoc($sql)) {

    if($groupsAlreadyPrint == '') {

      $groupName = $rows['szulo_ID'];
      $query2 = "SELECT * FROM cikkcsoportkod WHERE ID = '$groupName'";
      $sql2 = mysqli_query($conn, $query2); 
      $row = mysqli_fetch_assoc($sql2);

      echo 'You are here : <strong>'.$row['mn_HU'].'--->'.$rows['mn_HU'].'</strong> </br></br>';
      

      $groupsAlreadyPrint++;

    }

    $picture = 'pictures/'.$rows['cikkszam'].'_1.'.'jpg';
    
    if(file_exists($picture)) {

      $pictureExists = 1;

    } else {

      $picture = 'pictures/nopicture.jpg';

    }
     
     $productQuantity = productInBasketCounter($rows['cikkszam']);

    echo'
    <table class="table table-striped table-hover">
      <tbody>
        <tr>
          <form class="form-horizontal" action="index.php?a=basketOperation&b='.$b.'&c='.$rows['ID'].'&f='.$rows['cikkszam'].'" method="post">
          <fieldset>
          <td width="14%"><a href="'.$picture.'" data-lightbox="'.$picture.'" title="'.$rows['megnevzes'].'"><img src="'.$picture.'" alt="Mountain View" style="width:240px;"></a></td>
          <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;"><a href="index.php?a=details&b='.$b.'&e='.$rows['idka'].'">'.$rows['megnevzes'].'</a></td>
          <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">'.'('.$rows['cikkszam'].')'.'</td>
          <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">'.number_format($rows['kiskerar'],0,',','.').'</td>
          <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">'.substr($rows['informacio'], 0, 10).'...</td>';
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

    // echo '<a href="index.php?a=details&b='.$b.'&e='.$rows['ID'].'">'.$rows['megnevzes'].'</a>'.'('.$rows['cikkszam'].')'.
    //       $rows['kiskerar'].'&nbsp&nbsp'.substr($rows['informacio'], 0, 10).'...<a href="index.php?a=shop&b='.$b.'&c='.$rows['ID'].'"> Buy</a></br>';

    if($b == ''){

    header('Refresh:0; url=index.php?a=shop&b='.$b.'&c='.$c.'&f='.$f.'&e='.'1'.'&dzs='.$quantity.'&h='.$successSign.'');

  }

  }
  }

  function basketIndicator($quantity) {
    global $conn;
    global $b;
    global $c;
    global $f;
    global $e;
    global $h;
    $userid = '';
    
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

  function productInBasketCounter($f) {
    global $conn;

    if(isset($_SESSION['in'])) {
      $userid = $_SESSION['userid'];
    } else {
      $userid = session_id(); // itt ez nem jó !
    }

    // if(isset($_SESSION['in'])) {

      $query = "SELECT Darabszam FROM basket WHERE KosarTulajdonosa = '$userid' AND KosarCikkszamTartalma = '$f'";
      $sql = mysqli_query($conn, $query);

      $row = mysqli_fetch_assoc($sql);

      return $row['Darabszam'];

    // }
  }

?>