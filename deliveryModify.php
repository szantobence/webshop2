<?php

  if(filter_input(INPUT_POST, 'submit')) {

    $nev = filter_input(INPUT_POST, 'name');
    $artipus = filter_input(INPUT_POST, 'artipus');
    $jelszo = filter_input(INPUT_POST, 'jelszo');
    $email = filter_input(INPUT_POST, 'email');
    $varos = filter_input(INPUT_POST, 'varos');
    $iranyitoszam = filter_input(INPUT_POST, 'iranyito');
    $hazszam = filter_input(INPUT_POST, 'hazszam');
    $fizetesi= filter_input(INPUT_POST, 'payOption');
    $utca = filter_input(INPUT_POST, 'utca');
    $szallitasi = filter_input(INPUT_POST, 'deliveryOption');
  
    $query = "UPDATE user_szallitasicimek SET  email = '$email', nev = '$nev', varos = '$varos', iranyitoszam= '$iranyitoszam', utca = '$utca', hazszam = '$hazszam', 
                                  fizetesi = '$fizetesi', atvetel = '$szallitasi' WHERE id = '$f' ";

    mysqli_query($conn, $query);

  }

  $query = "SELECT * FROM user_szallitasicimek WHERE ID = '$f'";
  $addSqlModify = mysqli_query($conn, $query);
  $rows = mysqli_fetch_assoc($addSqlModify);

  echo '
<form class="form-horizontal" action="index.php?a=modify&f='.$f.'" style="margin: 0 auto; width:500px" method="post">
  <fieldset>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Email</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="email" value="'.$rows['email'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Name</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="name" value="'.$rows['nev'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">City</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="varos" value="'.$rows['varos'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Zip</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="iranyito" value="'.$rows['iranyitoszam'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Street</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="utca" value="'.$rows['utca'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">House</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="hazszam" value="'.$rows['hazszam'].'">
      </div>
    </div>
    <div class="form-group">
          <label class="col-lg-4 control-label">Method</label>
          <div class="col-lg-8">
            <div class="radio">
              <label>
                <input type="radio" name="payOption" id="optionsRadios1" value="Kartyas" checked="">
                Kartyas
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="payOption" id="optionsRadios2" value="Utanvet">
                Utanvet
              </label>
            </div>
          </div>
        </div>
    <div class="form-group">
          <label class="col-lg-4 control-label">Delivery</label>
          <div class="col-lg-8">
            <div class="radio">
              <label>
                <input type="radio" name="deliveryOption" id="optionsRadios3" value="Pick Pack Pont" checked="">
                Pick pack pont
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="deliveryOption" id="optionsRadios4" value="Hazhozszallitas">
                Házhozszállítás(+1000 Ft)
              </label>
            </div>
          </div>
        </div>
    <div class="form-group">
      <div class="col-lg-4 col-lg-offset-2">
        <input type="submit" class="btn btn-primary" name="submit" value="Modositas">
      </div>
    </div>
  </fieldset>
</form>
';




?>