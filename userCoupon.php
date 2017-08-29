<?php

$date = date("Y-m-d");
$picture = '';
$quantity = filter_input(INPUT_POST, 'quantity');

if(filter_input(INPUT_POST, 'submit')){

  $kuponKod = filter_input(INPUT_POST, 'kupon');

  $query = "SELECT * FROM cikktorzs WHERE kuponkod = '$kuponKod'";
  $sql = mysqli_query($conn, $query);
  $isCouponExists = mysqli_num_rows($sql);

  quantityAndItemInBasketCheck($quantity);
  
if($isCouponExists > 0) {

  while($rows = mysqli_fetch_assoc($sql)){

    if($date > $rows['Kuponkezdes'] && $date < $rows['Kuponvege']) {

              $picture = 'pictures/'.$rows['cikkszam'].'_1.'.'jpg';
            
            if(file_exists($picture)) {

              $pictureExists = 1;

            } else {

              $picture = 'pictures/nopicture.jpg';

            }
            
            $productQuantity = productInBasketCounter($rows['cikkszam']);

            $akcio = $rows['Kuponkodszazalek'] * 0.01;
            $kuponar = $rows['kiskerar'] * $akcio;
            $akciosar = $rows['kiskerar'] - $kuponar;

            echo'
            <table class="table table-striped table-hover">
              <tbody>
                <tr>
                  <form class="form-horizontal" action="index.php?a=userCoupon&b='.$b.'&c='.$rows['ID'].'&f='.$rows['cikkszam'].'" method="post">
                  <fieldset>
                  <td width="14%"><a href="'.$picture.'"><img src="'.$picture.'" alt="Mountain View" style="width:240px;"></a></td>
                  <td width="14%"><a href="index.php?a=details&b='.$b.'&e='.$rows['ID'].'">'.$rows['megnevzes'].'</a></td>
                  <td width="14%">'.'('.$rows['cikkszam'].')'.'</td>
                  <td width="14%">'.number_format($akciosar,0,',','.').'</td>
                  <td width="14%">'.substr($rows['informacio'], 0, 10).'...</td>';
            if($productQuantity > 0) {
              echo  '<td width="14%">Already in basket:'.$productQuantity.'</td>';
            } else { 
              echo  '<td width="14%"></td>';    
            }
            echo  '<td width="14%"><div class="form-group">
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
      } elseif($date < $rows['Kuponkezdes'] || $date > $rows['Kuponvege']) {

        // echo '
        //   <script type="text/javascript">
				// 	alert ("A kupon hibas, vagy meg nincsen hasznalatban!");
				// 	window.open ("index.php?a=userCoupon", "_self");
 				// 	</script> ';

      }
      
      // if($date < $rows['Kuponkezdes'] || $date > $rows['Kuponvege']) {

      // echo '
      //     <script type="text/javascript">
			// 		alert ("A kupon hibas, vagy meg nincsen hasznalatban!");
			// 		window.open ("index.php?a=userCoupon", "_self");
 			// 		</script> ';

      // }
    }

    } else {

      echo '
          <script type="text/javascript">
					alert ("A kupon hibas, vagy meg nincsen hasznalatban!");
					window.open ("index.php?a=userCoupon", "_self");
 					</script> ';

  }  
} 

echo'
<form class="form-horizontal" method="post" action="index.php?a=userCoupon" style="margin: 0 auto; width:500px">
  <fieldset>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-4 control-label">Adj meg egy kupont!</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="inputEmail" placeholder="Kupon" name="kupon">
        </div>
    </div>
    <div class="form-group">
      <div class="col-lg-7 col-lg-offset-4">
        <input type="submit" class="btn btn-primary" name="submit" value="Elkuldes">
      </div>
    </div>
  </fieldset>
</form>
';
//}

 function quantityAndItemInBasketCheck($quantity) {
    global $conn;
    global $b;
    global $c;
    global $f;
    global $successSign;

    if(isset($_SESSION['in'])) {

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

    $akcio = $row3['Kuponkodszazalek'] * 0.01;
    $kuponar = $row3['kiskerar'] * $akcio;
    $akciosar = $row3['kiskerar'] - $kuponar;

    if(mysqli_num_rows($sql) >= 1) {

      $query = "UPDATE basket SET Darabszam = '$quantity' WHERE KosarTulajdonosa = '$userid' AND KosarCikkszamtartalma = '$f'";
      mysqli_query($conn, $query);

      $successSign = 1;

    } elseif(mysqli_num_rows($sql) == 0 && isset($f)) {

      $query = "INSERT INTO basket (KosarTulajdonosa, KosarCikkszamTartalma, Darabszam, Discount, termekar) VALUES ( '$userid', '$f', '$quantity', 1, '$akciosar')";
      mysqli_query($conn, $query);

      $successSign = 1;
      $_SESSION['inbasket'] ++;

    }

    if($successSign == 1) {
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

      $userid = session_id();

    }

    if(isset($_SESSION['in'])) {

      $query = "SELECT Darabszam FROM basket WHERE KosarTulajdonosa = '$userid' AND KosarCikkszamTartalma = '$f'";
      $sql = mysqli_query($conn, $query);

      $row = mysqli_fetch_assoc($sql);

      return $row['Darabszam'];

    }
  }

?>