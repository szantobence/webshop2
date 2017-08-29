<?php

$oldpass = filter_input(INPUT_POST, 'oldpass');
$newpass = filter_input(INPUT_POST, 'newpass');
$newpassre = filter_input(INPUT_POST, 'newpassre');
//$oldpass = sha1($oldpass);
$oldpass = $oldpass;
$ujpass = '';

if(filter_input(INPUT_POST, 'submitadd')){

  $owner = $_SESSION['email'];
  $email2 = filter_input(INPUT_POST, 'email');
  $name2 = filter_input(INPUT_POST, 'Name');
  $city2 = filter_input(INPUT_POST, 'City');
  $zip2 = filter_input(INPUT_POST, 'Zip');
  $street2 = filter_input(INPUT_POST, 'Street');
  $house2 = filter_input(INPUT_POST, 'House');
  $payoption2 = filter_input(INPUT_POST, 'payOption');
  $deliveryoption2 = filter_input(INPUT_POST, 'deliveryOption');

  $addquery = "INSERT INTO user_szallitasicimek ( Tulaj, email, nev, varos, iranyitoszam, utca, hazszam, fizetesi, atvetel) VALUES 
            ('$owner','$email2','$name2','$city2','$zip2','$street2','$house2','$payoption2','$deliveryoption2')";

  mysqli_query($conn, $addquery);



}

if(filter_input(INPUT_POST, 'submitaddbill')){

  $owner = $_SESSION['email'];
  $email3 = filter_input(INPUT_POST, 'email');
  $name3 = filter_input(INPUT_POST, 'name');
  $city3 = filter_input(INPUT_POST, 'City');
  $zip3 = filter_input(INPUT_POST, 'Zip');
  $street3 = filter_input(INPUT_POST, 'Street');
  $house3 = filter_input(INPUT_POST, 'House');
  $regnum = filter_input(INPUT_POST, 'RegNum');
  $accnum = filter_input(INPUT_POST, 'AccNum');

  // // $addquerybill = "INSERT INTO szamlazasicimek (tulaj, nev, email, Varos, iranyitoszam, utca, hazszam, cegjegyzekszam, szamlaszam) VALUES 
  // --           ('$owner', '$name3','$email3','$city3','$zip3','$street3','$house3','$regnum','$accnum')";

  $queryacc = "SELECT * FROM szamlazasicimek WHERE tulaj = '$owner'";
  $sqlacc = mysqli_query($conn, $queryacc);
  $nums = mysqli_num_rows($sqlacc);

  if($nums >= 1) {
            
  $addquerybill = "UPDATE szamlazasicimek SET nev='$name3',
                  email='$email3',Varos='$city3',iranyitoszam='$zip3',utca='$street3',hazszam='$house3',cegjegyzekszam='$regnum',szamlaszam='$accnum' WHERE tulaj = '$owner'";                

  } else {

    $addquerybill = "INSERT INTO szamlazasicimek (tulaj, nev, email, Varos, iranyitoszam, utca, hazszam, cegjegyzekszam, szamlaszam) VALUES 
                               ('$owner', '$name3','$email3','$city3','$zip3','$street3','$house3','$regnum','$accnum')";
                               

  }

  mysqli_query($conn, $addquerybill);

}

if(filter_input(INPUT_POST, 'submit')){

$oldpass = sha1($oldpass);

$query = "SELECT jelszo FROM `users` WHERE email = '".$_SESSION['email']."' AND jelszo = '$oldpass' LIMIT 1 ";

//ATNEZNI !!!

$passsql = mysqli_query($conn, $query);

if(mysqli_num_rows($passsql) == 1){

 	if($newpass == $newpassre && strlen($newpass) > 0){

     $newpass = sha1($newpass);

 		//$newpass = sha1($newpass);
 		$query2 = "UPDATE users SET jelszo = '$newpass' WHERE jelszo = '$oldpass'";
 		$passupdate = mysqli_query($conn, $query2);




 		echo '  <script type="text/javascript">
					alert ("Logging out!");
					window.open ("index.php?a=logout", "_self");
 				</script> ';

 		$ujpass = 1;

 	} else {

 		echo '  <script type="text/javascript">
					alert ("Nem egyezik meg a megadott uj jelszo !");
 				</script> ';

 		$fenek = 1;

 	};

} else {

	echo '  <script type="text/javascript">
					alert ("Helytelen jelszo !");
 				</script> ';


	$rosszasag = 1;

}
}

if(isset($delete)) {

    $query = "DELETE FROM user_szallitasicimek WHERE ID = '$delete'";
    mysqli_query($conn, $query);

  }

echo'
<script>
  $(document).ready(function(){
        $(".password2").hide();
        $(".billing2").hide();
        $(".delivery2").hide();
        $(".addresses").hide();
        
        $(".delivery").click(function(){
          $(".delivery2").show(500);
          $(".billing2").hide(500);
          $(".password2").hide(500);
        
         });
         $(".billing").click(function(){
            $(".billing2").show(500);
            $(".delivery2").hide(500);
            $(".password2").hide(500);
        
         });
         $(".password").click(function(){
             $(".password2").show(500);
             $(".billing2").hide(500);
             $(".delivery2").hide(500);
        
         });
         $(".address").click(function(){
             $(".password2").hide(500);
             $(".billing2").hide(500);
             $(".delivery2").show(500);
             $(".addresses").show(500);
        
         });
  });
</script>';



  echo '
  <div class="delivery" style="margin: auto; width: 250px"><a href="#" class="btn btn-primary btn-sm" style="margin: auto; width: 250px">Delivery Address</a></div>
    <div class="delivery2" style="margin: auto; width: 250px">
      </br><form class="form-horizontal" action="index.php?a=profile" method="post">
        <fieldset>
          <div class="form-group">
           <label for="inputEmail" class="col-lg-2 control-label">Email</label>
            <div class="col-lg-10">
              <input type="text" name="email" class="form-control" id="inputEmail" placeholder="Email">
            </div>
          </div>
          <div class="form-group">
             <label for="inputEmail" class="col-lg-2 control-label">Name</label>
            <div class="col-lg-10">
              <input type="text" name="Name" class="form-control" id="inputEmail" placeholder="Name">
            </div>
          </div>
          <div class="form-group">
             <label for="inputEmail" class="col-lg-2 control-label">City</label>
            <div class="col-lg-10">
              <input type="text" name="City" class="form-control" id="inputEmail" placeholder="City">
            </div>
          </div>
          <div class="form-group">
             <label for="inputEmail" class="col-lg-2 control-label">Zip</label>
            <div class="col-lg-10">
              <input type="text" name="Zip" class="form-control" id="inputEmail" placeholder="Zip">
            </div>
          </div>
          <div class="form-group">
             <label for="inputEmail" class="col-lg-2 control-label">Street</label>
            <div class="col-lg-10">
              <input type="text" name="Street" class="form-control" id="inputEmail" placeholder="Street">
            </div>
          </div>
          <div class="form-group">
             <label for="inputEmail" class="col-lg-2 control-label">House</label>
            <div class="col-lg-10">
              <input type="text" name="House" class="form-control" id="inputEmail" placeholder="House">
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
          <div class="col-lg-10 col-lg-offset-4">
            <input type="submit" class="btn btn-primary" name="submitadd" value="Add">
          </div>
        </div>
        </fieldset>
      </form>
      <div class="address" style="margin: auto; width: 250px"><a href="#" class="btn btn-primary btn-sm" style="margin: auto; width: 250px"><font size="2"><u>Addresses</u></font></a></div>
      <div class="addresses">
      <table class="table table-striped table-hover " border="1">
        <thead>
          <tr class="success">
            <th width="10%">Name</th>
            <th width="10%">City</th>
            <th width="10%">Town</th>
            <th width="10%">Zip</th>
            <th width="10%">Street</th>
            <th width="10%">House</th>
            <th width="10%">Pay</th>
            <th width="10%">Delivery</th>
            <th width="10%"></th>
            <th width="10%"></th>
          </tr>
        </thead>
      </table>';

          $Tulaj = $_SESSION['email'];
          $queryAddress = "SELECT * FROM user_szallitasicimek WHERE Tulaj = '$Tulaj'";
          $sqlAddress = mysqli_query($conn, $queryAddress);
          while($rowAddress = mysqli_fetch_assoc($sqlAddress)) {

             echo'
              <table class="table table-striped table-hover ">
              <tbody>
                <tr class="success">
                  <td width="10%">'.$rowAddress['nev'].'</td>
                  <td width="10%">'.$rowAddress['email'].'</td>
                  <td width="10%">'.$rowAddress['varos'].'</td>
                  <td width="10%">'.$rowAddress['iranyitoszam'].'</td>
                  <td width="10%">'.$rowAddress['utca'].'</td>
                  <td width="10%">'.$rowAddress['hazszam'].'</td>
                  <td width="10%">'.$rowAddress['fizetesi'].'</td>
                  <td width="10%">'.$rowAddress['atvetel'].'</td>
                  <td width="10%"><a href="index.php?a=modify&f='.$rowAddress['ID'].'">Modositas</a></td>
                  <td width="10%"><a href="index.php?a=profile&delete='.$rowAddress['ID'].'">Torles</a></td>
                </tr>
              </tbody>
            </table> 
            ';

          }


 echo  '</div>
    </div>
  <div class="billing" style="margin: auto; width: 250px"><a href="#" class="btn btn-primary btn-sm" style="margin: auto; width: 250px">Billing Address</a></div>
    <div class="billing2" style="margin: auto; width: 250px"></br>';

      $queryAcc2 = "SELECT * FROM szamlazasicimek WHERE tulaj = '$Tulaj'";
      $sqlAcc2 = mysqli_query($conn, $queryAcc2);
      $rowacc = mysqli_fetch_assoc($sqlAcc2);

  echo '<form class="form-horizontal" action="index.php?a=profile" method="post">
        <fieldset>
          <div class="form-group">
           <label for="inputEmail" class="col-lg-2 control-label">Name</label>
            <div class="col-lg-10">
              <input type="text" name="name" class="form-control" id="inputEmail" placeholder="Email" value="'.$rowacc['email'].'">
            </div>
          </div>
          <div class="form-group">
             <label for="inputEmail" class="col-lg-2 control-label">Email</label>
            <div class="col-lg-10">
              <input type="text" name="email" class="form-control" id="inputEmail" placeholder="Name" value="'.$rowacc['nev'].'">
            </div>
          </div>
          <div class="form-group">
             <label for="inputEmail" class="col-lg-2 control-label">City</label>
            <div class="col-lg-10">
              <input type="text" name="City" class="form-control" id="inputEmail" placeholder="City" value="'.$rowacc['Varos'].'">
            </div>
          </div>
          <div class="form-group">
             <label for="inputEmail" class="col-lg-2 control-label">Zip</label>
            <div class="col-lg-10">
              <input type="text" name="Zip" class="form-control" id="inputEmail" placeholder="Zip" value="'.$rowacc['iranyitoszam'].'">
            </div>
          </div>
          <div class="form-group">
             <label for="inputEmail" class="col-lg-2 control-label">Street</label>
            <div class="col-lg-10">
              <input type="text" name="Street" class="form-control" id="inputEmail" placeholder="Street" value="'.$rowacc['utca'].'">
            </div>
          </div>
          <div class="form-group">
             <label for="inputEmail" class="col-lg-2 control-label">House</label>
            <div class="col-lg-10">
              <input type="text" name="House" class="form-control" id="inputEmail" placeholder="House" value="'.$rowacc['hazszam'].'">
            </div>
          </div>  
          <div class="form-group">
             <label for="inputEmail" class="col-lg-3 control-label">RegistrationNumber</label>
            <div class="col-lg-10">
              <input type="text" name="RegNum" class="form-control" id="inputEmail" placeholder="House" value="'.$rowacc['cegjegyzekszam'].'">
            </div>
          </div>  
          <div class="form-group">
             <label for="inputEmail" class="col-lg-3 control-label">AccNumber</label>
            <div class="col-lg-10">
              <input type="text" name="AccNum" class="form-control" id="inputEmail" placeholder="House" value="'.$rowacc['szamlaszam'].'">
            </div>
          </div>  
        <div class="form-group">
          <div class="col-lg-10 col-lg-offset-4">
            <input type="submit" class="btn btn-primary" name="submitaddbill" value="Add">
          </div>
        </div>
        </fieldset>
      </form>

    </div>
  <div class="password" style="margin: auto; width: 250px"><a class="btn btn-primary btn-sm" style="margin: auto; width: 250px">Password</a></div>
 ';

if($ujpass != 1){

echo '
<div class="password2">
  <form class="form-horizontal" action="index.php?a=profile" method= "post" style="margin: auto; width: 250px">
		<div class="form-group ';
		if($rosszasag == 1){
			echo 'has-error';
			}
		echo'">
		      <label for="inputPassword" class="col-lg-4 control-label">Oldpass    </label>
		      <div class="col-lg-10">
		        <input type="password" name= "oldpass" class="form-control" id="inputPassword" placeholder="Password">
		      </div>
		    </div>
		    <div class="form-group ';
			if($fenek == 1){
			echo 'has-error';
			}
			echo'">
		      <label for="inputPassword" class="col-lg-4 control-label">Newpass    </label>
		      <div class="col-lg-10">
		        <input type="password" name= "newpass" class="form-control" id="inputPassword" placeholder="Password">
		      </div>
		    </div>
		    <div class="form-group ';
			if($fenek == 1){
			echo 'has-error';
			}
			echo'">
		      <label for="inputPassword" class="col-lg-4 control-label">Newpass    </label>
		      <div class="col-lg-10">
		        <input type="password" name= "newpassre" class="form-control" id="inputPassword" placeholder="Password">
		      </div>
		    </div> 
		    <div class="form-group">
      		<div class="col-lg-10 col-lg-offset-4">
       			 <input type="submit" name="submit" class="btn btn-primary">
      		</div>
    	</div>
</form>
</div>

';

if(isset($_SESSION['in'])) {
  $costumerDeliverySpecs = deliverySpecsForASpecifiedUser();
}

userInfoChange($costumerDeliverySpecs);

}

previousOrders();

function previousOrders(){
	global $conn;

	$userID = session_id();

	if(isset($_SESSION['in'])) {

		$userID = $_SESSION['userid'];
		$query = "SELECT *, SUM(Ara * Darab) AS summa , MID(Datum,1,10) AS Datumsub FROM previousorders  WHERE RendelesCime = '$userID' GROUP BY DatumSmall";

	} else {

		$query = "SELECT cikktorzs.*, previousorders.* , SUM(Ara) AS summa , MID(Datum,1,10) AS Datumsub FROM previousorders INNER JOIN cikktorzs ON cikktorzs.cikkszam=previousorders.EddigiRendelesek WHERE previousorders.RendelesCime = '$userID' GROUP BY previousorders.DatumSmall";

	}

	$sql = mysqli_query($conn, $query);

		echo '<table align="center">
            <tbody>
              <tr>
                <td align="center"><font size="6"><strong>Previous Orders</strong></font></td>
              </tr>
            </tbody>
          </table></br>';

	echo '<table class="table table-striped table-hover" border="1">
            <tbody>
              <tr>
                <td width="20%"><font size="4"><strong></strong></font></td>
                <td width="20%"><font size="4"><strong>Date</strong></font></td>
                <td width="20%"><font size="4"><strong>Price</strong></font></td>
                <td width="20%"><font size="4"><strong>User Name</strong></font></td>
                <td width="20%"><font size="4"><strong></strong></font></td>
            </tbody>
          </table>';

	while($rows = mysqli_fetch_assoc($sql)) {

		 echo '<table class="table table-striped table-hover " border="1" >
            <tbody>
              <tr>
                <td width="20%"></td>
                <td width="20%"><a href="index.php?a=orderSpecs&b='.$rows['Datumsub'].'">'.$rows['Datumsub'].'</a></td>
                <td width="20%">'.number_format($rows['summa'],0,',','.').'</td>
                <td width="20%">'.$rows['Nev'].'</td>
                <td width="20%"></td>
              </tr>  
            </tbody>
          </table>';



	}
}

function userInfoChange($costumerDeliverySpecs) {

	echo ' <script>
          $(document).ready(function(){
            $(".ajax").hide();
            $(\'input[type="checkbox"]\').click(function(){
              if($(this).prop("checked")){
                $(".ajax").show(200);
                } else {
                $(".ajax").hide(200);
                }
            })
          });
       	</script>';

	echo'
		<form class="form-horizontal" style="margin: auto; width: 250px">
			<fieldset>
				<div class="checkbox">
							<label>
								<input type="checkbox"> UserInfo
							</label>
				</div>
			</fieldset>
		</form>
		</br>
		';

	echo'
	<div class="ajax">
	<form class="form-horizontal" action="index.php?a=infoChange" method="post" style="margin: auto; width: 250px">
      <fieldset>
        <div class="form-group">
          <label for="inputEmail" class="col-lg-3 control-label">Name</label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="inputEmail" name="name" placeholder="Name" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['Nev'] ; 
              echo '">
            </div>
       </div>
       <div class="form-group">
          <label for="inputEmail" class="col-lg-3 control-label">City</label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="inputEmail" name="city" placeholder="City" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['Varos'] ;
              echo '">
            </div>
       </div>
       <div class="form-group">
          <label for="inputEmail" class="col-lg-3 control-label">Zip code</label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="inputEmail" name="zipcode" placeholder="Zip code" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['iranyitoszam'] ;
              echo '">
            </div>
       </div>
			 <div class="form-group">
          <label for="inputEmail" class="col-lg-3 control-label">Street name</label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="inputEmail" name="streetname" placeholder="Street name" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['Utca'] ;
              echo '">
            </div>
       </div>
       <div class="form-group">
          <label for="inputEmail" class="col-lg-3 control-label">House Number</label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="inputEmail" name="housenumber" placeholder="House Number" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['hazszam'] ;
              echo '">
            </div>
       </div>
       <div class="form-group">
          <label for="inputEmail" class="col-lg-3 control-label">Floor</label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="inputEmail" name="floor" placeholder="Floor" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['emelet'] ;
              echo '">
            </div>
       </div>
       <div class="form-group">
          <label for="inputEmail" class="col-lg-3 control-label">Door</label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="inputEmail" name="door" placeholder="Door" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['ajto'] ;
              echo '">
            </div>
       </div>
       <div class="form-group">
          <label for="inputEmail" class="col-lg-3 control-label">Account number</label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="inputEmail" name="accountnumber" placeholder="Account Number" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['szamlaszam'] ;
              echo '">
            </div>
       </div>
       <div class="form-group">
          <div class="col-lg-10 col-lg-offset-2">
            <input type="submit" class="btn btn-primary" name="submit" value="Modositas">
          </div>
        </div>
      </fieldset>
    </form>
		</div>';

}

function deliverySpecsForASpecifiedUser() {
    global $conn;
    $email = $_SESSION['email'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $sql = mysqli_query($conn, $query);

    $row = mysqli_fetch_assoc($sql);

    return $row;

}

?>