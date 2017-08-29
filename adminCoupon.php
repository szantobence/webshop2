<?php

if(filter_input(INPUT_POST, 'submit')){

  $kuponKod = filter_input(INPUT_POST, 'Couponcode');
  $ervKezd =  filter_input(INPUT_POST, 'Ervkezd');
  $ervVeg =   filter_input(INPUT_POST, 'Ervveg');
  $kuponSzazalek =   filter_input(INPUT_POST, 'Couponpercent');
  $cikkek =   $_POST['Items'];

  $itemLength = sizeof($cikkek);

  for($i=0 ; $i < $itemLength ; $i++) {

    $query = "UPDATE cikktorzs SET Kuponkodszazalek = '$kuponSzazalek', kuponkod = '$kuponKod', Kuponkezdes = '$ervKezd', Kuponvege = '$ervVeg' WHERE ID = '$cikkek[$i]' ";
    mysqli_query($conn, $query);

  }
}

$query = "SELECT * FROM cikktorzs";
$sql = mysqli_query($conn, $query);

echo '
<form class="form-horizontal" method="post" action="index.php?a=admin&b=Coupon" style="margin: 0 auto; width:500px">
<fieldset>
    <div class="form-group">
      <label for="select" class="col-lg-2 control-label">Termekek</label>
      <div class="col-lg-7">
        <select multiple="multiple" class="form-control" name="Items[]">';
          while($row = mysqli_fetch_assoc($sql)){

          echo '<option value="'.$row['ID'].'">'.$row['megnevzes'].' '.$row['cikkszam'].'</option>';

          }
echo '  </select>
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Kuponkod</label>
      <div class="col-lg-7">
        <input type="text" class="form-control" id="inputEmail" placeholder="Code" name="Couponcode">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Ervenyesseg kezdete</label>
      <div class="col-lg-6">
        <input id="date" type="date" name="Ervkezd">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Ervenyesseg vege</label>
      <div class="col-lg-6">
        <input id="date" type="date" name="Ervveg">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Kedvezmeny szazalek</label>
      <div class="col-lg-7">
        <input type="number" class="form-control" id="inputEmail" placeholder="%" name="Couponpercent">
      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-7 col-lg-offset-2">
        <input type="submit" class="btn btn-primary" name="submit" value="Modositas">
      </div>
    </div>
</fieldset>
</form>
';

$query = "SELECT * FROM `cikktorzs` WHERE kuponkod != '' GROUP BY kuponkod";
$sql = mysqli_query($conn, $query);

table();

while($rows = mysqli_fetch_assoc($sql)) {

 //$filename = 'pictures/'.$rows['cikkszam'].'_'.'1'.'.'.'jpg';
    
    // if(file_exists($filename)) {

    //   $pictureExists = 1;

    // } else {

    //   $filename = 'pictures/nopicture.jpg';

    // }  

    echo'
    <table class="table table-striped table-hover">
      <tbody>
        <tr>
          <form class="form-horizontal">
            <fieldset>
             
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: center;"><a href="index.php?a=specCoupon&b='.$rows['kuponkod'].'">'.$rows['kuponkod'].'</a></td>
              
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
             
              <td width="14%" style="text-align: center; vertical-align: middle; padding: 5px; position: center;">Kuponkod</td>
              
              
            </fieldset>
          </form>
        </tr>
      </tbody>
    </table>
    ';

}

?>