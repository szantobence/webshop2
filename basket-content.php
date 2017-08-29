<?php

$kosarOsszesen = '';
$errorVariable = '';
$deliver = '';
 $osszprice = '';

if(isset($_SESSION['in']) && $b == 1) {

  $Userid = $_SESSION['userid'];

  $kosarOsszesen =basketIfLogin();

} elseif( $b == 1 ) { 

  $Userid = session_id();

  $kosarOsszesen = basketNotLogin();

} 



if(filter_input(INPUT_POST, "qtySubmit") && isset($q)) {

  $qty = filter_input(INPUT_POST, "reQty");
  $queryQty = "UPDATE basket SET darabszam = '$qty' WHERE id = '$q'";
  mysqli_query($conn, $queryQty);

   echo"
      <script type='text/javascript'>
      window.location.href = 'http://localhost/PHP-P/webshop/index.php?a=basket&b=1';
      </script>
    ";

}

if($a == 'basket' && $b == 1) {

  echo'
  <script>
    $(document).ready(function(){
          $(".couponCode").hide();
          
          $(".couponCode2").click(function(){
            $(".couponCode").show(500);
            
          
          });
    });
  </script>

  <div class="couponCode2" style="display: flex; flex-direction: column; align-items: flex-end;"><a href="#" class="btn btn-primary btn-sm" style="direction: rtl; width:200px;">Coupons</a></div></br>

  ';

  echo'
  <div class="couponCode">
  <form class="form-horizontal" action="index.php?a=basket&coupon=1" style="display: flex; flex-direction: column; align-items: flex-end;" method="post">
                                    <fieldset>
                                      <div class="form-group">
                                        <div class="col-lg-6 col-lg-offset-2">
                                          <input type="text" class="form-control" id="inputEmail" placeholder="Darab" name="coupon">
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-lg-6 col-lg-offset-2">
                                          <input type="submit" class="btn btn-primary" name="submit2">
                                        </div>
                                      </div>
                                    </fieldset>
                                  </form></div></br>';

  echo '<div class="btn-group btn-group-justified">
        <div class="col-lg-offset-5">
        <a href="index.php?a=basket&b=2&price='.$kosarOsszesen.'" class="btn btn-default">Next Page</a>
        </div>
        </div>';

}  

if(filter_input(INPUT_POST, 'submit2') && $coupon == 1) {

  $coupon = filter_input(INPUT_POST, 'coupon');
  CouponCode($coupon);

  echo"
      <script type='text/javascript'>
      window.location.href = 'http://localhost/00/webshop/index.php?a=basket&b=1';
      </script>
    ";

}                                

if($b == 'delete') {

  deleteItems();
  $_SESSION['inbasket'] --;

}


$costumerDeliverySpecs = "";
$formDatas = deliveryFormProcessor();
$errorVariable = $formDatas[8];

if(isset($_SESSION['in'])) {
  $costumerDeliverySpecs = deliverySpecsForASpecifiedUser();
}

if($a == 'basket' && $b == 2) {
  if(isset($_SESSION['num']) && $_SESSION['num'] > 0) {
    deliverySpecsAtOrdering($costumerDeliverySpecs);
  } else {

      echo '  <script type="text/javascript">
            alert ("Ures a kosarad!");
            </script> ';

      echo"
        <script type='text/javascript'>
        window.location.href = 'http://localhost/PHP-P/webshop/index.php?';
        </script>
      ";

  }
}


if(filter_input(INPUT_POST, 'submit') && $formDatas[8] != 1 && $formDatas[17] == 0) {
  if(isset($_SESSION['in'])) {
      
    orderSendingWhenLoggedin($formDatas);
      
  } else {

    orderSendingWhenLoggedOut($formDatas);

  }
}

if($a == 'basket' && $b == 3 && $q != 1 && isset($_SESSION['in'])) {

 thirdStepOrderSending();

} elseif($a == 'basket' && $b == 3 && $q != 1) {

  thirdStepOrderSendingnotlogin();

}

  if(isset($q) && $a == 'basket' && $b == 3 && filter_input(INPUT_POST, 'orderSendingSubmit')) {

     $_SESSION['inbasket'] = 0;

    $payoption = filter_input(INPUT_POST, 'payOption');

    $queryUpdatePay = "UPDATE previousorders SET FizetesiMod = '$payoption' WHERE Azon = '$dzs'";
    mysqli_query($conn, $queryUpdatePay);

    echo '  <script type="text/javascript">
					alert ("Thank you for the shopping!");
 					</script> ';

    echo"
        <script type='text/javascript'>
        window.location.href = 'http://localhost/00/webshop/index.php?order=".$dzs."&price=".$orderPrice."&h=1';
        </script>
      ";

}

function basketIfLogin() {
  global $conn;
  global $Userid;
  global $kosarOsszesen;
  $name = $_SESSION['email'];
  $Userid = $_SESSION['userid'];

  $query = "SELECT cikktorzs.*, basket.*
                FROM cikktorzs 
                INNER JOIN basket ON cikktorzs.cikkszam = basket.KosarCikkszamTartalma
                WHERE basket.KosarTulajdonosa = '$Userid'";

  $sql = mysqli_query($conn, $query);

  $numm = mysqli_num_rows($sql);
  $_SESSION['num'] = $numm;

  if($_SESSION['num'] == 0) {

    	echo '  <script type="text/javascript">
					alert ("Ures a kosarad!");
 					</script> ';

    echo"
      <script type='text/javascript'>
      window.location.href = 'http://localhost/PHP-P/webshop/index.php?';
      </script>
    ";

  }

  if($numm > 0) {
  echo '<table class="table table-striped table-hover ">
          <thead>
              <tr>
                <th width="20%">Name</th>
                <th width="20%">Price(Gross Price)</th>
                <th width="20%">Quantity</th>
                <th width="20%">Sum</th>
                <th width="20%"></th>
              </tr>
            </thead>
          </table>';

  }        

  while($rows = mysqli_fetch_assoc($sql)) {
    $warehouse = 0;

    if($rows['Darabszam'] > $rows['Darab']) {

      $warehouse = 1;
      $rows['Darabszam'] = $rows['Darab'];

    }
    
    if($rows['Discount'] == 1){
 
      $akcio = $rows['Kuponkodszazalek'] * 0.01;
      $kuponar = $rows['kiskerar'] * $akcio;
      $rows['kiskerar'] = $rows['kiskerar'] - $kuponar;

      $discountinteger = 1;

    }
    //echo $rows['megnevzes'].$rows['Darabszam'].$rows['Darab'];

    $osszesen = $rows['termekar'] * $rows['Darabszam'];

    echo'<table class="table table-striped table-hover ">
            <tbody>
              <tr>
                <td width="20%"><a href="index.php?a=details&e='.$rows['ID'].'">'.$rows['megnevzes'].'</a></td>
                <td width="20%">'.number_format($rows['termekar'],0,',','.').'</td>
                <td width="20%">    
                              <form class="form-horizontal" action="index.php?a=basket&q='.$rows['id'].'" id="quantity" method="post">
                                  <fieldset>
                                    <div class="form-group">
                                      <div class="col-lg-6">
                                        <input type="text" class="form-control" id="inputEmail" name="reQty" placeholder="Darab" value="'.$rows['Darabszam'].'">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-10 col-lg-offset-1">
                                        <input type="submit" class="btn btn-primary" name="qtySubmit">
                                      </div>
                                    </div>
                                  </fieldset>
                                </form></td>
                <td width="20%">'.number_format($osszesen, 0,',','.').'</td>
                <td width="20%"><a href="index.php?a=basket&b=delete&f='.$rows['id'].'"> Delete </a></td>';
                // if($warehouse == 1) {echo '<td width="13%"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true">Csokkeno arukeszlet!</span></td>';}
    echo      '</tr>
            </tbody>
          </table>';

     $kosarOsszesen = $kosarOsszesen + $osszesen; 

   // echo $rows['megnevzes'].'</br>'.$rows['kiskerar'].'</br>'.$rows['KosarTulajdonosa'].'<a href="index.php?a=basket&b=delete&f='.$rows['id'].'"> Delete </a></br></br>';

  }

  if($kosarOsszesen > 0){
    echo '<table class="table table-striped table-hover ">
            <thead>
                <tr>
                  <th width="20%"></th>
                  <th width="20%"></th>
                  <th width="20%"></th>
                  <th width="20%">Basket Sum : '.number_format($kosarOsszesen,0,',','.').' Ft</th>
                  <th width="20%"></th>
                </tr>
              </thead>
            </table>';
  }

  return $kosarOsszesen;
}

function basketNotLogin() {

  global $conn;
  global $Userid;
  global $kosarOsszesen;
  $name = session_id();

  $query = "SELECT cikktorzs.*, basket.*
                FROM cikktorzs 
                INNER JOIN basket ON cikktorzs.cikkszam = basket.KosarCikkszamTartalma
                WHERE basket.KosarTulajdonosa = '$Userid'";

  $sql = mysqli_query($conn, $query);

  $numm = mysqli_num_rows($sql);
  $_SESSION['num'] = $numm;

  if($_SESSION['num'] == 0) {

    	echo '  <script type="text/javascript">
					alert ("Ures a kosarad!");
 					</script> ';

    echo"
      <script type='text/javascript'>
      window.location.href = 'http://localhost/PHP-P/webshop/index.php?';
      </script>
    ";

  }

  if($numm > 0) {
  echo '<table class="table table-striped table-hover ">
          <thead>
              <tr>
                <th width="20%">Name</th>
                <th width="20%">Price(Gross Price)</th>
                <th width="20%">Quantity</th>
                <th width="20%">Sum</th>
                <th width="20%">
                                  </fieldset>
                                </form></td></th>
              </tr>
            </thead>
          </table>';

  }        

  while($rows = mysqli_fetch_assoc($sql)) {
    $warehouse = 0;

    if($rows['Darabszam'] > $rows['Darab']) {

      $warehouse = 1;
      $rows['Darabszam'] = $rows['Darab'];

    }
    
    if($rows['Discount'] == 1){
 
      $akcio = $rows['Kuponkodszazalek'] * 0.01;
      $kuponar = $rows['kiskerar'] * $akcio;
      $rows['kiskerar'] = $rows['kiskerar'] - $kuponar;

      $discountinteger = 1;

    }
    //echo $rows['megnevzes'].$rows['Darabszam'].$rows['Darab'];

    $osszesen = $rows['termekar'] * $rows['Darabszam'];

    echo'<table class="table table-striped table-hover ">
            <tbody>
              <tr>
                <td width="20%"><a href="index.php?a=details&e='.$rows['ID'].'">'.$rows['megnevzes'].'</a></td>
                <td width="20%">'.number_format($rows['termekar'],0,',','.').'</td>
                <td width="20%">    
                              <form class="form-horizontal" action="index.php?a=basket&q='.$rows['id'].'&b=1" id="quantity" method="post">
                                  <fieldset>
                                    <div class="form-group">
                                      <div class="col-lg-10">
                                        <input type="text" class="form-control" id="inputEmail" name="reQty" placeholder="Darab" value="'.$rows['Darabszam'].'">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-lg-10 col-lg-offset-2">
                                        <input type="submit" class="btn btn-primary" name="qtySubmit">
                                      </div>
                                    </div>
                                  </fieldset>
                                </form></td>
                <td width="20%">'.number_format($osszesen,0,',','.').'</td>
                <td width="20%"><a href="index.php?a=basket&b=delete&f='.$rows['id'].'"> Delete </a></td>';
                // if($warehouse == 1) {echo '<td width="13%"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true">Csokkeno arukeszlet!</span></td>';}
    echo      '</tr>
            </tbody>
          </table>';

     $kosarOsszesen = $kosarOsszesen + $osszesen; 

   // echo $rows['megnevzes'].'</br>'.$rows['kiskerar'].'</br>'.$rows['KosarTulajdonosa'].'<a href="index.php?a=basket&b=delete&f='.$rows['id'].'"> Delete </a></br></br>';

  }

  if($kosarOsszesen > 0){
    echo '<table class="table table-striped table-hover ">
            <thead>
                <tr>
                  <th width="20%"></th>
                  <th width="20%"></th>
                  <th width="20%"></th>
                  <th width="20%">Basket Sum : '.number_format($kosarOsszesen,0,',','.').' Ft</th>
                  <th width="20%"></th>
                </tr>
              </thead>
            </table>';
  }

  return $kosarOsszesen;
}

function orderSendingWhenLoggedin($formDatas) {
  global $Userid;
  global $conn;
  global $orderPrice;
  $name = $_SESSION['email'];
  $timestamp = date("ymdHis");
  $Azonosito = $_SESSION['userid'].'_'.$timestamp;
  $timestamp2 = date("ymd");
  $Userid = $_SESSION['userid'];

  $kosarOsszesen = $orderPrice;

  //$query = "SELECT * FROM basket WHERE KosarTulajdonosa = '$Userid'";
  $query = "SELECT cikktorzs.*, basket.*
                FROM cikktorzs 
                INNER JOIN basket ON cikktorzs.cikkszam = basket.KosarCikkszamTartalma
                WHERE basket.KosarTulajdonosa = '$Userid'";
  $sql = mysqli_query($conn, $query);

    if(($formDatas[1] == 'Hazhozszallitas' && $kosarOsszesen < 100000) || $formDatas[1] == 'adottHazhozszallitas' && $kosarOsszesen < 100000){

      $query22 = "INSERT INTO previousorders (EddigiRendelesek, Datum, DatumSmall, RendelesCime, Darab, Azon, Ara) VALUES (1, '$timestamp', '$timestamp2', '".$Userid."', '1', '".$Azonosito."', '1000' )";
      $sql22 = mysqli_query($conn, $query22);

      $szallitas = 1;
      
    }

  while($rows = mysqli_fetch_assoc($sql)) {

    $cikkszam = $rows['KosarCikkszamTartalma'];
    $darab = $rows['Darabszam'];

    $query5 = "SELECT * FROM cikktorzs WHERE cikkszam = '$cikkszam'";
    $sql5 = mysqli_query($conn, $query5);
    $cikkrows = mysqli_fetch_assoc($sql5);

    if($darab > $cikkrows['Darab']) { 

      $darab = $cikkrows['Darab'];

    }

    if($rows['Discount'] == 1){
 
      $akcio = $rows['Kuponkodszazalek'] * 0.01;
      $kuponar = $rows['kiskerar'] * $akcio;
      $rows['kiskerar'] = $rows['kiskerar'] - $kuponar;

      $discountinteger = 1;

    }
      
    $query8 = "INSERT INTO previousorders (EddigiRendelesek, Datum, DatumSmall, RendelesCime, Darab, Azon, osszrendeles, Ara) VALUES ('$cikkszam', '$timestamp', '$timestamp2', '".$rows['KosarTulajdonosa']."', '$darab', '".$Azonosito."','$kosarOsszesen', '".$rows['termekar']."' )";
    $sql1 = mysqli_query($conn, $query8);  

    $query9 = "UPDATE cikktorzs SET Darab = Darab - '$darab' WHERE cikkszam = '$cikkszam'";
    mysqli_query($conn, $query9);

      
  }

  $query = "UPDATE previousorders SET RendelesHelye = '$formDatas[3]', AtveteliLehetoseg = '$formDatas[1]', Nev = '$formDatas[2]', Iranyitoszam = '$formDatas[4]', Hazszam = '$formDatas[5]', Megjegyzes = '$formDatas[6]', Utca = '$formDatas[7]', 
            sznev = '$formDatas[9]', szemail = '$formDatas[10]', szvaros = '$formDatas[11]', sziranyitoszam = '$formDatas[12]', szutca = '$formDatas[13]', szhazszam = '$formDatas[14]', szcegjegyzekszam = '$formDatas[15]', szszamlaszam = '$formDatas[16]', emelet = '$formDatas[18]', ajto = '$formDatas[19]' WHERE Datum = '$timestamp' OR Azon = '$Azonosito'";

  mysqli_query($conn, $query);

  $queryUpdate = "UPDATE users SET Nev='$formDatas[2]', Varos='$formDatas[3]', iranyitoszam ='$formDatas[4]', Utca ='$formDatas[7]', hazszam ='$formDatas[5]', emelet ='$formDatas[18]', ajto ='$formDatas[19]' WHERE id = '$Userid' ";
  mysqli_query($conn, $queryUpdate);

  // echo '  <script type="text/javascript">
	// 				alert ("Thank you for the shopping!");
 	// 				</script> ';

  echo"
      <script type='text/javascript'>
      window.location.href = 'http://localhost/00/webshop/index.php?order=".$Azonosito."&price=".$kosarOsszesen."&deliver=".$szallitas."&a=basket&b=3&e=".$timestamp."&dzs=".$Azonosito."';
      </script>
    ";

  $query = "DELETE FROM basket WHERE KosarTulajdonosa = '$Userid'";
  mysqli_query($conn, $query);

}

function orderSendingWhenLoggedOut($formDatas) {

  global $Userid;
  global $conn;
  global $kosarOsszesen;
  $name = session_id();
  $timestamp = date("ymdHis");
  $Azonosito = session_id().'_'.$timestamp;
  $timestamp2 = date("ymd");
  $Userid = session_id();

  //$query = "SELECT * FROM basket WHERE KosarTulajdonosa = '$Userid'";
  $query = "SELECT cikktorzs.*, basket.*
                FROM cikktorzs 
                INNER JOIN basket ON cikktorzs.cikkszam = basket.KosarCikkszamTartalma
                WHERE basket.KosarTulajdonosa = '$Userid'";
  $sql = mysqli_query($conn, $query);

    if($formDatas[1] == 'Hazhozszallitas' && $kosarOsszesen < 100000){

      $query22 = "INSERT INTO previousorders (EddigiRendelesek, Datum, DatumSmall, RendelesCime, Darab, Azon, Ara) VALUES (1, '$timestamp', '$timestamp2', '".$name."', '1', '".$Azonosito."', '1000' )";
      $sql22 = mysqli_query($conn, $query22);

      $szallitas = 1;
      
    }

  while($rows = mysqli_fetch_assoc($sql)) {

    $cikkszam = $rows['KosarCikkszamTartalma'];
    $darab = $rows['Darabszam'];

    $query5 = "SELECT * FROM cikktorzs WHERE cikkszam = '$cikkszam'";
    $sql5 = mysqli_query($conn, $query5);
    $cikkrows = mysqli_fetch_assoc($sql5);

    if($darab > $cikkrows['Darab']) { 

      $darab = $cikkrows['Darab'];

    }

    if($rows['Discount'] == 1){
 
      $akcio = $rows['Kuponkodszazalek'] * 0.01;
      $kuponar = $rows['kiskerar'] * $akcio;
      $rows['kiskerar'] = $rows['kiskerar'] - $kuponar;

      $discountinteger = 1;

    }
      
    $query8 = "INSERT INTO previousorders (EddigiRendelesek, Datum, DatumSmall, RendelesCime, Darab, Azon, Ara) VALUES ('$cikkszam', '$timestamp', '$timestamp2', '".$rows['KosarTulajdonosa']."', '$darab', '".$Azonosito."', '".$rows['termekar']."' )";
    $sql1 = mysqli_query($conn, $query8);  

    $query9 = "UPDATE cikktorzs SET Darab = Darab - '$darab' WHERE cikkszam = '$cikkszam'";
    mysqli_query($conn, $query9);

      
  }

  $query = "UPDATE previousorders SET RendelesHelye = '$formDatas[3]', FizetesiMod = '$formDatas[0]', AtveteliLehetoseg = '$formDatas[1]', Nev = '$formDatas[2]', Iranyitoszam = '$formDatas[4]', Hazszam = '$formDatas[5]', Megjegyzes = '$formDatas[6]', Utca = '$formDatas[7]', 
            sznev = '$formDatas[9]', szemail = '$formDatas[10]', szvaros = '$formDatas[11]', sziranyitoszam = '$formDatas[12]', szutca = '$formDatas[13]', szhazszam = '$formDatas[14]', szcegjegyzekszam = '$formDatas[15]', szszamlaszam = '$formDatas[16]' , emelet = '$formDatas[18]', ajto = '$formDatas[19]' WHERE Datum = '$timestamp' OR Azon = '$Azonosito'";
  
  mysqli_query($conn, $query);


  $queryUpdate = "UPDATE users SET Nev='$formDatas[2]', Varos='$formDatas[3]', iranyitoszam ='$formDatas[4]', Utca ='$formDatas[7]', hazszam ='$formDatas[5]', emelet ='$formDatas[18]', ajto ='$formDatas[19]' WHERE id = '$Userid' ";
  mysqli_query($conn, $queryUpdate);

  // echo '  <script type="text/javascript">
	// 				alert ("Thank you for the shopping!");
 	// 				</script> ';

  echo"
      <script type='text/javascript'>
      window.location.href = 'http://localhost/00/webshop/index.php?order=".$Azonosito."&price=".$kosarOsszesen."&deliver=".$szallitas."&a=basket&b=3&e=".$timestamp."&dzs=".$Azonosito."';
      </script>
    ";

  $query = "DELETE FROM basket WHERE KosarTulajdonosa = '$Userid'";
  mysqli_query($conn, $query);
  
  // echo '  <script type="text/javascript">
	// 				alert ("Thank you for the shopping!");
 	// 				</script> ';

  // echo"
  //     <script type='text/javascript'>
  //     window.location.href = 'http://localhost/00/webshop/index.php?order=".$Azonosito."&price=".$kosarOsszesen."&deliver=".$szallitas."';
  //     </script>
  //   ";

  // $query = "DELETE FROM basket WHERE KosarTulajdonosa = '$Userid'";
  // mysqli_query($conn, $query);

  // $_SESSION['inbasket'] = 0;

}

function deleteItems() {
  global $conn;
  global $f;

  $query = "DELETE FROM `basket` WHERE id = '$f'";
  mysqli_query($conn, $query);

 echo"
      <script type='text/javascript'>
      window.location.href = 'http://localhost/00/webshop/index.php?a=basket&b=1';
      </script>
    ";

    $_SESSION['inbasket'] == $_SESSION['inbasket'] - 1;

}

function deliverySpecsAtOrdering($costumerDeliverySpecs) {

global $errorVariable;
global $conn;
global $orderPrice;
if(isset($_SESSION['in'])){
$email = $_SESSION['email'];
$queryCimek = "SELECT * FROM user_szallitasicimek WHERE Tulaj = '$email'";
$sqlCimek = mysqli_query($conn, $queryCimek);
}

echo'
  <script type="text/javascript">

    $(document).ready(function(){
      $(".hazhoz").show();
      $(".taroltCim").hide();
      $(".pickpack").hide();

      $(\'input[type="radio"]\').click(function(){
      if($(this).attr("value")=="Hazhozszallitas"){
    
      $(".hazhoz").show(200);
      $(".taroltCim").hide();
      $(".pickpack").hide();
      
      }
      if($(this).attr("value")=="Pick Pack Pont"){
      
      $(".hazhoz").hide();
      $(".taroltCim").hide();
      $(".pickpack").show(200);

      }
      if($(this).attr("value")=="adottHazhozszallitas"){
      
      $(".hazhoz").hide();
      $(".taroltCim").show(200);
      $(".pickpack").hide();

      }
      if($(this).attr("value")=="Shop"){
      
      $(".hazhoz").hide();
      $(".taroltCim").hide();
      $(".pickpack").hide();

      }
    });
    });

    </script>
    ';

    echo'
  <script type="text/javascript">

    $(document).ready(function(){

      $(".accbevitel").hide();

      $(\'input[type="radio"]\').click(function(){
      if($(this).attr("value")=="megadott"){
    
      $(".accbevitel").hide();
      
      }
      if($(this).attr("value")=="felvitt"){
      
      $(".accbevitel").show(200);

      }
      
    });
    });

    </script>
    ';

  echo '
    <form class="form-horizontal" action="index.php?a=basket&b=2&price='.$orderPrice.'" method="post">
      <fieldset>
        <div class="form-group">
          <label class="col-lg-4 control-label">Delivery Method ?</label>
          <div class="col-lg-8">
            <div class="radio">
              <label>
                <input type="radio" name="deliveryOption" id="optionsRadios3" value="Pick Pack Pont" >
                Pick pack point
              </label>
            </div>';
          if(isset($_SESSION['in'])){
         echo'   <div class="radio">
              <label>
                <input type="radio" name="deliveryOption" id="optionsRadios4" value="adottHazhozszallitas">
                Delivery To Saved Address';if($orderPrice < 100000 ){ echo'(+1000 Ft)';}
            echo '</label>
            </div>';
            }
         echo' <div class="homeDelivery">
            <div class="radio">
              <label>
                <input type="radio" name="deliveryOption" id="optionsRadios4" value="Hazhozszallitas" checked="">
                Delivery to my address';if($orderPrice < 100000 ){ echo'(+1000 Ft)';}
            echo'
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="deliveryOption" id="optionsRadios4" value="Shop">
                Shop Receipt
              </label>
            </div>
          </div>
        </div>
         <div class="form-group">
          <label class="col-lg-4 control-label">Billing ?</label>
          <div class="col-lg-8">';
          if(isset($_SESSION['in'])) {
            $accQuery5 = "SELECT * FROM szamlazasicimek WHERE tulaj = '$email'";
            $sqlacc = mysqli_query($conn, $accQuery5);
            $rowacc = mysqli_fetch_assoc($sqlacc);

           echo '<div class="radio">
              <label>
                <input type="radio" name="accOption" id="optionsRadios3" value="megadott" checked="">
               Saved (Name : '.$rowacc['nev'].'&nbsp- Email : '.$rowacc['email'].'&nbsp- City : '.$rowacc['Varos'].'&nbsp- Zip : '.$rowacc['iranyitoszam'].'&nbsp- Street : '.$rowacc['utca'].'&nbsp- House : '.$rowacc['hazszam'].'&nbsp- Registration : '.$rowacc['cegjegyzekszam'].'&nbsp- Account : '.$rowacc['szamlaszam'].')
              </label>
            </div>';
            }
         echo  '
              <div class="radio">
              <label>
                <input type="radio" name="accOption" id="optionsRadios3" value="felvitt">
                Modify
              </label>
            </div>
            </div>
            <div class="accbevitel">
              <div class="form-group ';
              if(isset($formDatas[17]) && $formDatas[17] == 1){
                echo 'has-error';
              }
              echo'"><label for="inputEmail" class="col-lg-3 control-label">Name</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control" id="inputEmail" placeholder="name" name="accname">
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail" class="col-lg-3 control-label">Email</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control" id="inputEmail" placeholder="Email" name="accemail">
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail" class="col-lg-3 control-label">City</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control" id="inputEmail" placeholder="city" name="acccity">
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail" class="col-lg-3 control-label">Zip</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control" id="inputEmail" placeholder="zip" name="acczip">
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail" class="col-lg-3 control-label">Street</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control" id="inputEmail" placeholder="street" name="accstreet">
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail" class="col-lg-3 control-label">House</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control" id="inputEmail" placeholder="house" name="acchouse">
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail" class="col-lg-3 control-label">Reg</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control" id="inputEmail" placeholder="reg" name="accreg">
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail" class="col-lg-3 control-label">Account</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control" id="inputEmail" placeholder="Account" name="accacc">
                </div>
              </div>
            </div>';
        if(isset($_SESSION['in'])){
       echo '<div class="taroltCim">
        <div class="form-group">
        <label class="col-lg-4 control-label">Melyik címet választod ?</label>
        <div class="col-lg-10">';
        while($rowCim = mysqli_fetch_assoc($sqlCimek)) {

          echo'<div class="radio" align="center">
              <label>
                <input type="radio" name="deliveryOption2" id="optionsRadios4" value="'.$rowCim['ID'].'">
                '.$rowCim['Tulaj'].'&nbsp'.$rowCim['email'].'&nbsp'.$rowCim['nev'].'&nbsp'.$rowCim['varos'].'&nbsp'.$rowCim['iranyitoszam'].'&nbsp'.$rowCim['utca'].'&nbsp'.$rowCim['hazszam'].'&nbsp'.$rowCim['fizetesi'].'&nbsp'.$rowCim['atvetel'].'
              </label>
            </div></br></br>';

        }
        
      echo '</div>
            </div>
            </div></br></br></br></br></br>';
            }

      echo '<div class="pickpack">
            <div class="form-group">
              <label for="inputEmail" class="col-lg-3 control-label">Name</label>
              <div class="col-lg-4">
                <input type="text" class="form-control" id="inputEmail" name="pickpackname" placeholder="Email">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail" class="col-lg-3 control-label">Email</label>
              <div class="col-lg-4">
                <input type="text" class="form-control" id="inputEmail" name="pickpackemail" placeholder="Email">
              </div>
            </div>
              <div class="form-group">
                <label for="select" class="col-lg-3 control-label">Addresses</label>
                  <div class="col-lg-4">
                    <select multiple="" class="form-control" name="pickPackOption">
                      <option value="Budapest-Váci-Utca-13.-MOL">Budapest-Váci-Utca-13.-MOL</option>
                      <option value="Budapest-Vas-Utca-56.-MOL">Budapest-Vas-Utca-56.-MOL</option>
                      <option value="Budapest-Réti-Utca-43.-TESCO">Budapest-Réti-Utca-43.-TESCO</option>
                    </select>
                  </div>
              </div> 
            </div>';

      echo  '<div class="hazhoz">
        <div class="form-group ';
        if($errorVariable == 1){
        echo 'has-error';
        }
        echo '"><label for="inputEmail" class="col-lg-3 control-label">Name</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="inputEmail" name="name" placeholder="Name" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['Nev'] ; 
              echo '">
            </div>
       </div>
       <div class="form-group ';
       if($errorVariable == 1){
        echo 'has-error';
        }
       echo'">
          <label for="inputEmail" class="col-lg-3 control-label">City</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="inputEmail" name="city" placeholder="City" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['Varos'] ;
              echo '">
            </div>
       </div>
        <div class="form-group ';
       if($errorVariable == 1){
        echo 'has-error';
        }
       echo'">
          <label for="inputEmail" class="col-lg-3 control-label">Zip code</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="inputEmail" name="zipcode" placeholder="Zip code" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['iranyitoszam'] ;
              echo '">
            </div>
       </div>
        <div class="form-group ';
       if($errorVariable == 1){
        echo 'has-error';
        }
       echo'">
          <label for="inputEmail" class="col-lg-3 control-label">Street name</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="inputEmail" name="streetname" placeholder="Street name" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['Utca'] ;
              echo '">
            </div>
       </div>
        <div class="form-group ';
       if($errorVariable == 1){
        echo 'has-error';
        }
       echo'">
          <label for="inputEmail" class="col-lg-3 control-label">House Number</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="inputEmail" name="housenumber" placeholder="House Number" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['hazszam'] ;
              echo '">
            </div>
       </div>
       <div class="form-group ';
       if($errorVariable == 1){
        echo 'has-error';
        }
       echo'">
          <label for="inputEmail" class="col-lg-3 control-label">Floor</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="inputEmail" name="floor" placeholder="Floor" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['emelet'] ;
              echo '">
            </div>
       </div>
       <div class="form-group ';
       if($errorVariable == 1){
        echo 'has-error';
        }
       echo'">
          <label for="inputEmail" class="col-lg-3 control-label">Door</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="inputEmail" name="door" placeholder="Door" value="';
              if(isset($_SESSION['in'])) echo $costumerDeliverySpecs['ajto'] ;
              echo '">
            </div>
       </div>
       </div>
       <div class="form-group">
          <label for="textArea" class="col-lg-3 control-label">Comment</label>
            <div class="col-lg-5">
              <textarea class="form-control" rows="3" id="textArea" name="comment"></textarea>
            </div>
       </div>
       
       <div class="form-group">
          <div class="col-lg-10 col-lg-offset-3">
            <input type="submit" class="btn btn-primary" name="submit" value="Next Step">
          </div>
        </div>  
      </fieldset>
    </form>'; 

}

function deliverySpecsForASpecifiedUser() {
    global $conn;
    $email = $_SESSION['email'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $sql = mysqli_query($conn, $query);

    $row = mysqli_fetch_assoc($sql);

    return $row;

}

function deliveryFormProcessor() {

  global $errorVariable;
  global $conn;
  if(isset($_SESSION['in'])){
  $owner = $_SESSION['email'];
  }

  if(filter_input(INPUT_POST, 'submit')) {
    $deliveryoption = filter_input(INPUT_POST, 'deliveryOption');
    $accOption2 = filter_input(INPUT_POST, 'accOption');
    $pickpackoption = filter_input(INPUT_POST, 'payOption');
    if($deliveryoption == 'adottHazhozszallitas') {

      $adottCimId = filter_input(INPUT_POST, 'deliveryOption2');
      
      $queryAdottCim = "SELECT * FROM user_szallitasicimek WHERE ID = '$adottCimId'";
      $sqlAdottCim = mysqli_query($conn, $queryAdottCim);
      $adottCimAssoc = mysqli_fetch_assoc($sqlAdottCim);

      $payoption = $adottCimAssoc['fizetesi'];
      $deliveryoption = $adottCimAssoc['atvetel'];
      $addedname = $adottCimAssoc['nev'];
      $city = $adottCimAssoc['varos'];
      $zipcode = $adottCimAssoc['iranyitoszam'];
      $street = $adottCimAssoc['utca'];
      $housenumber = $adottCimAssoc['hazszam'];
      $floor = $adottCimAssoc['emelet'];
      $door = $adottCimAssoc['ajto'];
      $comment = filter_input(INPUT_POST, 'comment');

    } elseif($deliveryoption == 'Hazhozszallitas') {

    $payoption = filter_input(INPUT_POST, 'payOption');
    $deliveryoption = filter_input(INPUT_POST, 'deliveryOption');
    $addedname = filter_input(INPUT_POST, 'name');
    $city = filter_input(INPUT_POST, 'city');
    $zipcode = filter_input(INPUT_POST, 'zipcode');
    $street = filter_input(INPUT_POST, 'streetname');
    $housenumber = filter_input(INPUT_POST, 'housenumber');
    $floor = filter_input(INPUT_POST, 'floor');
    $door = filter_input(INPUT_POST, 'door');
    $comment = filter_input(INPUT_POST, 'comment');

    } elseif($deliveryoption == 'Pick Pack Pont') {

      $payoption = filter_input(INPUT_POST, 'payOption');
      $deliveryoption = filter_input(INPUT_POST, 'pickPackOption');
      $comment = filter_input(INPUT_POST, 'comment');
      $addedname = filter_input(INPUT_POST, 'pickpackname');
      $addedemail = filter_input(INPUT_POST, 'pickpackemail');
      $city = 'n';
      $zipcode = 'n';
      $street = 'n';
      $housenumber = 'n';

    }

    if($addedname == '' || $city == '' || $zipcode == '' || $street == '' || $housenumber == '') {

      $errorVariable = 1;

    }

    if($accOption2 == 'megadott') {

      $accQuery = "SELECT * FROM szamlazasicimek WHERE tulaj = '$owner'";
      $accSql = mysqli_query($conn, $accQuery);
      $accRow = mysqli_fetch_assoc($accSql);

      $accname = $accRow['nev'];
      $accemail = $accRow['email'];
      $acccity = $accRow['Varos'];
      $acczip = $accRow['iranyitoszam'];
      $accstreet = $accRow['utca'];
      $acchouse = $accRow['hazszam'];
      $accreg = $accRow['cegjegyzekszam'];
      $accacc = $accRow['szamlaszam'];

    } else {

      $accname = filter_input(INPUT_POST, 'accname');
      $accemail = filter_input(INPUT_POST, 'accemail');
      $acccity = filter_input(INPUT_POST, 'acccity');
      $acczip = filter_input(INPUT_POST, 'acczip');
      $accstreet = filter_input(INPUT_POST, 'accstreet');
      $acchouse = filter_input(INPUT_POST, 'acchouse');
      $accreg = filter_input(INPUT_POST, 'accreg');
      $accacc = filter_input(INPUT_POST, 'accacc');      
      
    }

    if($accname == '' || $accemail == '') {
      $hiba = 1;
    } else {
      $hiba = 0;
    }

    return array($payoption, $deliveryoption, $addedname, $city, $zipcode, $housenumber, $comment, $street, $errorVariable,$accname,$accemail,$acccity,$acczip,$accstreet,$acchouse,$accreg, $accacc, $hiba,$floor,$door);

  }
}

function CouponCode($coupon) {
global $conn;
$date = date("y-m-d");

if(isset($_SESSION['in'])) {

      $userid = $_SESSION['userid'];

    } else {

      $userid = session_id();

    }

$query = "SELECT *
                FROM basket 
                WHERE kuponkod = '$coupon' AND kuponkezd < '$date' AND kuponveg > '$date' AND KosarTulajdonosa = '$userid'";

$sql = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($sql)) {

  $akcio = $row['kuponszazalek'] * 0.01;
  $kuponar = $row['termekar'] * $akcio;
  $akciosar = $row['termekar'] - $kuponar;
  $ID = $row['KosarCikkszamTartalma'];
  
  $query2 = "UPDATE basket SET termekar = '$akciosar', hasznaltkupon = 1 WHERE kuponkod = '$coupon' AND KosarCikkszamTartalma = '$ID' AND hasznaltkupon = 0";
  $sql15 = mysqli_query($conn, $query2);

}
}

function thirdStepOrderSending() {
  global $conn;
  global $e;
  global $dzs;
  global $orderPrice;
  global $osszprice;

  $queryAll = "SELECT previousorders.*, users.*, previousorders.Nev AS rendNev , previousorders.emelet AS emelet1 , previousorders.ajto AS ajto1 FROM previousorders INNER JOIN users ON previousorders.Nev = users.Nev WHERE previousorders.azon = '$dzs'";
  $sqlAll = mysqli_query($conn, $queryAll);
  $rowsAll = mysqli_fetch_assoc($sqlAll);

  echo'<div valign="center" align="center">
  <strong><u>Costumer\'s information:</u></strong></br></br>
  <strong>Name: </strong>'.$rowsAll['rendNev'].'</br></br>
  <strong>Email: </strong>'.$rowsAll['email'].'</br></br>
  <strong>Delivery Address: </strong>'.$rowsAll['RendelesHelye'].'</br><p style="padding-left: 8em">'.$rowsAll['Iranyitoszam'].'</br>'.$rowsAll['Utca'].'</br>'.$rowsAll['Hazszam'].'</br>'.$rowsAll['emelet1'].'</br>'.$rowsAll['ajto1'].'</p></br>
  <strong>Billing Address: </strong>'.$rowsAll['sznev'].'</br><p style="padding-left: 8em">'.$rowsAll['szvaros'].'</br>'.$rowsAll['sziranyitoszam'].'</br>'.$rowsAll['szutca'].'</br>'.$rowsAll['szhazszam'].'</br>'.$rowsAll['szcegjegyzekszam'].'</br>'.$rowsAll['szszamlaszam'].'</p></br>
  <strong>Reception Method: </strong>'.$rowsAll['AtveteliLehetoseg'].'</br></br>
  <strong>Comment: </strong>'.$rowsAll['Megjegyzes'].'-</br></br>
  
      <table class="table table-striped table-hover" border="2" style="border-bottom: solid 1px black; border-left: solid 1px black; border-collapse: collapse; border: solid 1px black;">
            <thead>
                <tr>
                  <th width="25%">Quantity</th>
                  <th width="25%">Item Name</th>
                  <th width="25%">Price</th>
                  <th width="25%">Sum</th>
                </tr>
              ';

  $queryAll2 = "SELECT previousorders.*, cikktorzs.*, previousorders.Darab AS darab1 FROM previousorders INNER JOIN cikktorzs ON previousorders.EddigiRendelesek = cikktorzs.cikkszam WHERE previousorders.azon = '$dzs'";

  $sqlAll2 = mysqli_query($conn, $queryAll2);
  while($rowsAll2 = mysqli_fetch_assoc($sqlAll2)) {

    $ossz = $rowsAll2['Ara'] * $rowsAll2['darab1'];

        echo '<tr>
                  <th width="25%">'.$rowsAll2['darab1'].'</th>
                  <th width="25%">'.$rowsAll2['megnevzes'].'</th>
                  <th width="25%">'.number_format($rowsAll2['Ara'],0,',','.').'</th>
                  <th width="25%">'.number_format($ossz,0,',','.').'</th>
                </tr>'; 

    $osszprice = $osszprice + $ossz;

  }

  echo '<tr>      <th width="25%"></th>
                  <th width="25%"></th>
                  <th width="25%"></th>
                  <th width="25%">'.number_format($osszprice,0,',','.').'</th>
                  </tr>
          </thead>
            </table>
            </div>';

  echo'
  <form class="form-horizontal" action="index.php?a=basket&b=3&price='.$orderPrice.'&q=1&dzs='.$dzs.'&e='.$e.'" method="post">
      <fieldset>
        <div class="form-group">
          <label class="col-lg-4 col-lg-offset-2 control-label">Payment method ?</label>
          <div class="col-lg-8 col-lg-offset-5">
            <div class="radio">
              <label>
                <input type="radio" name="payOption" id="optionsRadios1" value="CreditCard" checked="">
                CreditCard
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="payOption" id="optionsRadios2" value="c.o.d.">
                c.o.d.
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="payOption" id="optionsRadios2" value="Cash">
                Cash
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="payOption" id="optionsRadios2" value="TransferInAdvance">
                Transfer In Advance
              </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-10 col-lg-offset-5">
            <input type="submit" class="btn btn-primary" name="orderSendingSubmit" value="Send Order !">
          </div>
        </div>
        </fieldset>
        </form>
        ';

  if(isset($q) && $a == 'basket' && $b == 3 && filter_input(INPUT_POST, 'orderSendingSubmit')) {

    $payoption = filter_input(INPUT_POST, 'payOption');

    $queryUpdatePay = "UPDATE previousorders SET FizetesiMod = '$payoption' WHERE Azon = '$dzs'";
    mysqli_query($conn, $queryUpdatePay);

    

    echo '  <script type="text/javascript">
					alert ("Thank you for the shopping!");
 					</script> ';

    echo"
        <script type='text/javascript'>
        window.location.href = 'http://localhost/00/webshop/index.php?order=".$dzs."&price=".$orderPrice."&h=1';
        </script>
      ";
      

  }

}

function thirdStepOrderSendingnotlogin() {

  global $conn;
  global $e;
  global $dzs;
  global $orderPrice;
  global $osszprice;

  $queryAll = "SELECT * FROM previousorders WHERE azon = '$dzs'";
  $sqlAll = mysqli_query($conn, $queryAll);
  $rowsAll = mysqli_fetch_assoc($sqlAll);

  echo'<div valign="center" align="center">
  <strong><u>Costumer\'s information:</u></strong></br></br>
  <strong>Name: </strong>'.$rowsAll['Nev'].'</br></br>
  <strong>Delivery Address: </strong>'.$rowsAll['RendelesHelye'].'</br><p style="padding-left: 8em">'.$rowsAll['Iranyitoszam'].'</br>'.$rowsAll['Utca'].'</br>'.$rowsAll['Hazszam'].'</br>'.$rowsAll['emelet'].'</br>'.$rowsAll['ajto'].'</p></br>
  <strong>Billing Address: </strong>'.$rowsAll['sznev'].'</br><p style="padding-left: 8em">'.$rowsAll['szvaros'].'</br>'.$rowsAll['sziranyitoszam'].'</br>'.$rowsAll['szutca'].'</br>'.$rowsAll['szhazszam'].'</br>'.$rowsAll['szcegjegyzekszam'].'</br>'.$rowsAll['szszamlaszam'].'</p></br>
  <strong>Reception Method: </strong>'.$rowsAll['AtveteliLehetoseg'].'</br></br>
  <strong>Comment: </strong>'.$rowsAll['Megjegyzes'].'-</br></br>
  
      <table class="table table-striped table-hover" border="2" style="border-bottom: solid 1px black; border-left: solid 1px black; border-collapse: collapse; border: solid 1px black;">
            <thead>
                <tr>
                  <th width="25%">Quantity</th>
                  <th width="25%">Item Name</th>
                  <th width="25%">Price</th>
                  <th width="25%">Sum</th>
                </tr>
              ';

  $queryAll2 = "SELECT previousorders.*, cikktorzs.*, previousorders.Darab AS darab1 FROM previousorders INNER JOIN cikktorzs ON previousorders.EddigiRendelesek = cikktorzs.cikkszam WHERE previousorders.azon = '$dzs'";

  $sqlAll2 = mysqli_query($conn, $queryAll2);
  while($rowsAll2 = mysqli_fetch_assoc($sqlAll2)) {

    $ossz = $rowsAll2['Ara'] * $rowsAll2['darab1'];

        echo '<tr>
                  <th width="25%">'.$rowsAll2['darab1'].'</th>
                  <th width="25%">'.$rowsAll2['megnevzes'].'</th>
                  <th width="25%">'.number_format($rowsAll2['Ara'],0,',','.').'</th>
                  <th width="25%">'.number_format($ossz,0,',','.').'</th>
                </tr>'; 

    $osszprice = $osszprice + $ossz;

  }

  echo '<tr>      <th width="25%"></th>
                  <th width="25%"></th>
                  <th width="25%"></th>
                  <th width="25%">'.number_format($osszprice,0,',','.').'</th>
                  </tr>
          </thead>
            </table>
            </div>';

  echo'
  <form class="form-horizontal" action="index.php?a=basket&b=3&price='.$orderPrice.'&q=1&dzs='.$dzs.'&e='.$e.'" method="post">
      <fieldset>
        <div class="form-group">
          <label class="col-lg-4 col-lg-offset-2 control-label">Payment method ?</label>
          <div class="col-lg-8 col-lg-offset-5">
            <div class="radio">
              <label>
                <input type="radio" name="payOption" id="optionsRadios1" value="CreditCard" checked="">
                CreditCard
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="payOption" id="optionsRadios2" value="c.o.d.">
                c.o.d.
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="payOption" id="optionsRadios2" value="Cash">
                Cash
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="payOption" id="optionsRadios2" value="TransferInAdvance">
                Transfer In Advance
              </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-10 col-lg-offset-5">
            <input type="submit" class="btn btn-primary" name="orderSendingSubmit" value="Send Order !">
          </div>
        </div>
        </fieldset>
        </form>
        ';

  if(isset($q) && $a == 'basket' && $b == 3 && filter_input(INPUT_POST, 'orderSendingSubmit')) {

    $payoption = filter_input(INPUT_POST, 'payOption');

    $queryUpdatePay = "UPDATE previousorders SET FizetesiMod = '$payoption' WHERE Azon = '$dzs'";
    mysqli_query($conn, $queryUpdatePay);

    

    echo '  <script type="text/javascript">
					alert ("Thank you for the shopping!");
 					</script> ';

    echo"
        <script type='text/javascript'>
        window.location.href = 'http://localhost/00/webshop/index.php?order=".$Azonosito."&price=".$orderPrice."&h=1';
        </script>
      ";
      
  }

}

?>