<?php

if(filter_input(INPUT_POST, 'submit')) {

  $nev = filter_input(INPUT_POST, 'nev');
  $artipus = filter_input(INPUT_POST, 'artipus');
  $jelszo = filter_input(INPUT_POST, 'Jelszo');
  $email = filter_input(INPUT_POST, 'Email');
  $varos = filter_input(INPUT_POST, 'Varos');
  $iranyitoszam = filter_input(INPUT_POST, 'Iranyitoszam');
  $hazszam = filter_input(INPUT_POST, 'Hazszam');
  $szuletesiido = filter_input(INPUT_POST, 'szuletesiido');
  $telefonszam = filter_input(INPUT_POST, 'Telefonszam');
  $szamlaszam = filter_input(INPUT_POST, 'Szamlaszam');
  $cegneve = filter_input(INPUT_POST, 'Cegneve');
 
  $query = "UPDATE users SET  Nev = '$nev', Artipus = '$artipus', jelszo = '$jelszo', email= '$email', Varos = '$varos', iranyitoszam = '$iranyitoszam', 
                                hazszam = '$hazszam', szuletesi_ido = '$szuletesiido',
                                telefonszam = '$telefonszam', szamlaszam = '$szamlaszam', ceg_neve = '$cegneve' WHERE id = '$f' ";

  mysqli_query($conn, $query);

}

if(filter_input(INPUT_POST, 'passSubmit')) {

  randomPass();

}

$query = "SELECT * FROM users WHERE id = '$f'";
$sql = mysqli_query($conn, $query);
$rows = mysqli_fetch_assoc($sql);

echo ' <script>
          $(document).ready(function(){
            $(".randpass").hide();
            $(\'input[type="checkbox"]\').click(function(){
              if($(this).prop("checked")){
                $(".randpass").show(200);
                } else {
                $(".randpass").hide(200);
                }
            })
          });
       </script>
';

echo '
<form class="form-horizontal" action="index.php?a=admin&b=Usermodify&f='.$f.'" style="margin: 0 auto; width:500px" method="post">
  <fieldset>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Nev</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="nev" value="'.$rows['Nev'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Artipus</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="artipus" value="'.$rows['Artipus'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Jelszo</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="Jelszo" value="'.$rows['jelszo'].'">
        <div class="checkbox">
          <label>
            <input type="checkbox"> Enable random pass
          </label>
        </div>
      </div>
    </div>
    <div class="randpass">
    <div class="form-group">
      <div class="col-lg-6 col-lg-offset-3">
        <input type="submit" class="btn btn-primary" name="passSubmit" value="RandomPass">
      </div>
    </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Email</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="Email" value="'.$rows['email'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Varos</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="Varos" value="'.$rows['Varos'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Iranyitoszam</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="Iranyitoszam" value="'.$rows['iranyitoszam'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Hazszam</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="Hazszam" value="'.$rows['hazszam'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Szuletesi ido</label>
      <div class="col-lg-6">
        <input id="date" type="date" name="szuletesiido" value="'.$rows['szuletesi_ido'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Telefonszam</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="Telefonszam" value="'.$rows['telefonszam'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Szamlaszam</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="Szamlaszam" value="'.$rows['szamlaszam'].'">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Ceg neve</label>
      <div class="col-lg-6">
        <input type="text" class="form-control" id="inputEmail" name="Cegneve" value="'.$rows['ceg_neve'].'">
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

function randomPass() {

  $randpass = '';
  $email = $_SESSION['email'];
  global $conn;
  global $f;

  $chars = array('a', 'b' ,'c', 'd', 'e' ,'f', 'g', 'h' ,'i', 'j', 'k' ,'l', 'A', 'B' ,'C', 'D', 'E' ,'F');

	for ($i=0; $i <= 8 ; $i++) { 

		$rand = rand(1, 8);
		
		$randpass .= $chars[$rand];

	}

  $query = "SELECT * FROM users WHERE id = '$f'";
  $sql = mysqli_query($conn, $query);
  $rows = mysqli_fetch_assoc($sql);

  $email = $rows['email'];

  $query = "UPDATE users SET jelszo = '$randpass' WHERE email = '$email'";
  mysqli_query($conn, $query);

  echo '<div align="center"><h4>Your brand new password is : '.$randpass.' !</h4></div></br></br>';

}

?>