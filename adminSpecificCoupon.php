<?php

$query = "SELECT * FROM `cikktorzs` WHERE kuponkod = '$b' ORDER BY kuponkod";
$sql = mysqli_query($conn, $query);

table();

while($rows = mysqli_fetch_assoc($sql)) {

 $filename = 'pictures/'.$rows['cikkszam'].'_'.'1'.'.'.'jpg';
    
    if(file_exists($filename)) {

      $pictureExists = 1;

    } else {

      $filename = 'pictures/nopicture.jpg';

    }  

    echo'
    <table class="table table-striped table-hover">
      <tbody>
        <tr>
          <form class="form-horizontal">
            <fieldset>
              <td width="14%"><a href="'.$filename.'"><img src="'.$filename.'" alt="Mountain View" style="width:240px;"></a></td>
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;"><a href="index.php?a=details&b='.$b.'&e='.$rows['ID'].'">'.$rows['megnevzes'].'</a></td>
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">'.'('.$rows['cikkszam'].')'.'</td>
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">'.number_format($rows['kiskerar'],0,',','.').'</td>
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">'.substr($rows['informacio'], 0, 10).'...</td>
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">'.$rows['kuponkod'].'</td>
            </fieldset>
          </form>
        </tr>
      </tbody>
    </table>
    ';

}

function table() {

echo'
    <table class="table table-striped table-hover">
      <tbody>
        <tr>
          <form class="form-horizontal">
            <fieldset>
              <td width="14%">Kep</td>
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">Megnevezes</a></td>
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">Cikkszam</td>
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">Kiskerar</td>
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">Informacio</td>
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: relative;">Kuponkod</td>
            </fieldset>
          </form>
        </tr>
      </tbody>
    </table>
    ';

}

?>