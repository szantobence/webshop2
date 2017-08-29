<?php

$userID = session_id();

if(isset($_SESSION['in'])) {

		$userID = $_SESSION['userid'];
		$query = "SELECT cikktorzs.*, previousorders.* FROM previousorders INNER JOIN cikktorzs ON cikktorzs.cikkszam=previousorders.EddigiRendelesek WHERE previousorders.RendelesCime = '$userID' AND DatumSmall = '$b' AND previousorders.EddigiRendelesek != 1";

	} else {

		$query = "SELECT cikktorzs.*, previousorders.* FROM previousorders INNER JOIN cikktorzs ON cikktorzs.cikkszam=previousorders.EddigiRendelesek WHERE previousorders.RendelesCime = '$userID' AND DatumSmall = '$b' AND previousorders.EddigiRendelesek != 1";

	}


$sql2 = mysqli_query($conn, $query);
$rows2 = mysqli_fetch_assoc($sql2);

		echo '<table align="center">
            <tbody>
              <tr>
                <td align="center"><font size="6"><strong>Previous Orders On '.$rows2['DatumSmall'].'</strong></font></td>
              </tr>
            </tbody>
          </table></br>';

	echo '<table class="table table-striped table-hover" border="1">
            <tbody>
              <tr>
                <td width="18%"><font size="4"><strong></strong></font></td>
                <td width="11%"><font size="4"><strong>ID</strong></font></td>
                <td width="11%"><font size="4"><strong>Date</strong></font></td>
                <td width="8%"><font size="4"><strong>Delivery</strong></font></td>
                <td width="11%"><font size="4"><strong>Payment</strong></font></td>
                <td width="10%"><font size="4"><strong>Address</strong></font></td>
                <td width="11%"><font size="4"><strong>Street</strong></font></td>
                <td width="9%"><font size="4"><strong>House</strong></font></td>
                <td width="11%"><font size="4"><strong>Item</strong></font></td>
            </tbody>
          </table>';

  $sql = mysqli_query($conn, $query);

  if(filter_input(INPUT_POST, 'submit')) {

    while($rows = mysqli_fetch_assoc($sql)) {

      $cikk = $rows['EddigiRendelesek'];

      if($cikk != 1) {

        $itemInsertQuery = "SELECT * FROM cikktorzs WHERE cikkszam = '$cikk'";
        $itemInsertSql = mysqli_query($conn, $itemInsertQuery);
        $itemRow = mysqli_fetch_assoc($itemInsertSql);

        $cikkszam = $itemRow['cikkszam'];
        $ar = $itemRow['kiskerar'];
        $kuponszazalek = $itemRow['Kuponkodszazalek'];
        $kod = $itemRow['kuponkod'];
        $kodveg = $itemRow['Kuponvege'];
        $kodkezd = $itemRow['Kuponkezdes'];
        $tulaj = $_SESSION['userid'];

        $inserQuery = "INSERT INTO basket (KosarCikkszamTartalma, KosarTulajdonosa, Darabszam, termekar, kuponkod, kuponszazalek, kuponkezd, kuponveg, hasznaltkupon)
                        VALUES ('$cikkszam','$tulaj',1,'$ar','$kod','$kuponszazalek','$kodkezd','$kodveg',0)";
        mysqli_query($conn, $inserQuery);

        $_SESSION['inbasket'] = $_SESSION['inbasket'] + 1;

      }
    }

    echo"
      <script type='text/javascript'>
      window.location.href = 'http://localhost/00/webshop/index.php?a=orderSpecs&b=".$b."';
      </script>
    ";

  }

	while($rows = mysqli_fetch_assoc($sql)) {

    $picture = 'pictures/'.$rows['cikkszam'].'_1.'.'jpg';
    
    if(file_exists($picture)) {

      $pictureExists = 1;

    } else {

      $picture = 'pictures/nopicture.jpg';

    }

		 echo '<table class="table table-striped table-hover " border="1" >
            <tbody>
              <tr>
                <td width="11%"><a href="'.$picture.'" data-lightbox="'.$picture.'" title="'.$rows['megnevzes'].'"><img src="'.$picture.'" alt="Mountain View" style="width:240px;"></a></td>
                <td width="11%">'.$rows['Azon'].'</td>
                <td width="11%">'.$rows['Datum'].'</td>
                <td width="11%">'.$rows['AtveteliLehetoseg'].'</td>
                <td width="11%">'.$rows['FizetesiMod'].'</td>
                <td width="11%">'.$rows['RendelesHelye'].'</td>
                <td width="11%">'.$rows['Utca'].'</td>
                <td width="11%">'.$rows['Hazszam'].'</td>
                <td width="11%">'.$rows['cikkszam'].'</td>
              </tr>  
            </tbody>
          </table>';

	
}

echo'<form class="form-horizontal" action="index.php?a=orderSpecs&b='.$b.'" method="post">
  <fieldset>
  <div class="form-group">
      <div class="col-lg-10 col-lg-offset-5">
        <input type="submit" class="btn btn-primary" name="submit" value="To Basket">
      </div>
    </div>
  </fieldset>
</form>';

?>