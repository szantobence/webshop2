<?php
$huha = 0;

if(filter_input(INPUT_POST, 'submit')) {

  $Azonosito = filter_input(INPUT_POST, 'Azonosito');
  $Hun = filter_input(INPUT_POST, 'Hun');
  $Eng = filter_input(INPUT_POST, 'Eng');
  $Azon = filter_input(INPUT_POST, 'Azon');
  $ID = '';
  $IDnumber = 0;

  $query = "SELECT * FROM cikkcsoportkod WHERE Azon = '$Azonosito'";
  $sql = mysqli_query($conn, $query);
  $IDnumber = mysqli_num_rows($sql);

  if($Azon == 'Uj focsoport' && $IDnumber == 0){
    $ID = 0;
  } else {

    // $query = "SELECT * FROM cikkcsoportkod WHERE mn_HU = '$Azon'";
    // $sql = mysqli_query($conn, $query);
    // $ID = mysqli_fetch_assoc($sql);
    // $ID = implode("" ,$ID);

    $query = "SELECT * FROM cikkcsoportkod WHERE Azon = '$Azonosito'";
    $sql = mysqli_query($conn, $query);
    $IDnumber = mysqli_num_rows($sql);
    
  }

  if($IDnumber == 0) {

    if($Eng == '' || $Azonosito == '') {

      $huha = 1;

    } else {

      $query2 = "INSERT INTO cikkcsoportkod(Azon, mn_HU, mn_EN, szulo_ID) VALUES ('$Azonosito','$Hun','$Eng','$ID')";
      mysqli_query($conn, $query2);
      
    }

  } else {

    echo '  <script type="text/javascript">
					alert ("Mar letezik ilyen csoport!");
 					</script> ';

  }
}

$query1 = "SELECT * FROM cikkcsoportkod";
$sql = mysqli_query($conn, $query1);


echo'
<form class="form-horizontal" action="index.php?a=admin&b=Groups" style="margin: 0 auto; width:500px" method="post">
  <fieldset>
    <div class="form-group ';
    if($huha == 1){
      echo'has-error';
    }
echo'"><label for="inputEmail" class="col-lg-2 control-label">Azonosito</label>
        <div class="col-lg-5">
         <input type="text" class="form-control" id="inputEmail" placeholder="Azonosito" name="Azonosito">
        </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Hun</label>
        <div class="col-lg-5">
         <input type="text" class="form-control" id="inputEmail" placeholder="Hun" name="Hun">
        </div>
    </div>
    <div class="form-group ';
    if($huha == 1){
      echo'has-error';
    }
echo'"><label for="inputEmail" class="col-lg-2 control-label">Eng</label>
        <div class="col-lg-5">
         <input type="text" class="form-control" id="inputEmail" placeholder="Eng" name="Eng">
        </div>
    </div>
    <div class="form-group">
      <label for="select" class="col-lg-2 control-label">Szulo_ID</label>
      <div class="col-lg-5">
        <select class="form-control" id="select" name="Azon">
               <option>Uj focsoport</option>';
     while($rows = mysqli_fetch_assoc($sql)) {   
          echo'<option>'.$rows['mn_HU'].'</option>';
        }
   echo'</select>
      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
        <input type="submit" class="btn btn-primary" name="submit">
      </div>
    </div>
  </fieldset>
</form>
';

?>