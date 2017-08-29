<!DOCTYPE html>
<html>
<head>

  <script src="code.jquery.min.js"></script>
	<script src="jsbootswatch.min.js"></script>
	<link rel="stylesheet" type="text/css" href="bootswatchcss.css">
	<link rel="stylesheet" href="font-awesome/css/font-awesome.css">
	<script src="jquery-1.10.2.min.js"></script>
  <link href="lightbox.css" rel="stylesheet">
  <script src="lightbox-2.6.min.js"></script>
	
<title></title>
</head>
<body>

<?php
session_start();



$a = filter_input(INPUT_GET, 'a');
$b = filter_input(INPUT_GET, 'b');
$c = filter_input(INPUT_GET, 'c');
$d = filter_input(INPUT_GET, 'd');
$e = filter_input(INPUT_GET, 'e');
$f = filter_input(INPUT_GET, 'f');
$h = filter_input(INPUT_GET, 'h');
$dzs = filter_input(INPUT_GET, 'dzs');
$q = filter_input(INPUT_GET, 'q');
$user = filter_input(INPUT_GET, 'user');
$discount = filter_input(INPUT_GET, 'discount');
$delete = filter_input(INPUT_GET, 'delete');
$coupon = filter_input(INPUT_GET, 'coupon');
$orderRespond = filter_input(INPUT_GET, 'order');
$orderPrice = filter_input(INPUT_GET, 'price');
$deliver = filter_input(INPUT_GET, 'deliver');

include('connect.php');

 if($a == 'basketOperation') {

 include('basketOperation.php');
 include('inbasket.php');

 }

include('menu.php');

if(isset($orderRespond) && $h == 1) {

	if($deliver == 1){
		$orderPrice += 1000;
	}

	echo' <table class="table table-striped table-hover " border="1">
            	<thead>
                <tr class="info">
                  <th width="11%">Varos</th>
                  <th width="11%">Delivery</th>
                  <th width="11%">PayM</th>
                  <th width="11%">Zip</th>
                  <th width="11%">Street</th>
                  <th width="11%">House</th>
                  <th width="11%">Price</th>
                  <th width="11%">ID</th>
                  <th width="11%">Date</th>
                </tr>
              </thead>
            </table>';

	$queryOrder = "SELECT previousorders.*, cikktorzs.*, previousorders.Darab AS darab1 FROM previousorders INNER JOIN cikktorzs ON previousorders.EddigiRendelesek = cikktorzs.cikkszam WHERE previousorders.Azon = '$orderRespond'";
	$orderSql = mysqli_query($conn, $queryOrder);
	$rowOrder = mysqli_fetch_assoc($orderSql);

		  echo' <table class="table table-striped table-hover " border="1">
            	<thead>
                <tr class="info">
                  <th width="11%">'.$rowOrder['RendelesHelye'].'</th>
                  <th width="11%">'.$rowOrder['AtveteliLehetoseg'].'</th>
                  <th width="11%">'.$rowOrder['FizetesiMod'].'</th>
                  <th width="11%">'.$rowOrder['Iranyitoszam'].'</th>
                  <th width="11%">'.$rowOrder['Utca'].'</th>
                  <th width="11%">'.$rowOrder['Hazszam'].'</th>
                  <th width="11%">'.$orderPrice.'Ft</th>
                  <th width="11%">'.$rowOrder['Azon'].'</th>
                  <th width="11%">'.$rowOrder['Datum'].'</th>
                </tr>
              </thead>
            </table>';

							echo' <table class="table table-striped table-hover " border="1">
            	<thead>
                <tr class="success">
                  <th width="11%">Name</th>
                  <th width="11%">Price</th>
                  <th width="11%">Quantity</th>
                  <th width="11%">Sum</th>
                  <th width="11%"></th>
                  <th width="11%"></th>
                  <th width="11%"></th>
                  <th width="11%"></th>
                  <th width="11%"></th>
                </tr>
              </thead>
            </table>';
	
	$orderSql2 = mysqli_query($conn, $queryOrder);
	while($rowOrder2 = mysqli_fetch_assoc($orderSql2)) {
						
			 echo' <table class="table table-striped table-hover " border="1">
            	<thead>
                <tr class="success">
                  <th width="11%">'.$rowOrder2['megnevzes'].'</th>
                  <th width="11%">'.$rowOrder2['Ara'].'</th>
                  <th width="11%">'.$rowOrder2['darab1'].'</th>
                  <th width="11%">'.$rowOrder2['Ara'] * $rowOrder2['darab1'].'</th>
                  <th width="11%"></th>
                  <th width="11%"></th>
                  <th width="11%"></th>
                  <th width="11%"></th>
                  <th width="11%"></th>
                </tr>
              </thead>
            </table>';

	}					

 }

if($a == 'loginproc') {
	include('receive.php');
}

if($a == 'login') {
	include('login.php');
}

if($a == 'logout') {
  session_unset();
	header("Refresh:0; url=index.php");
}

if($a == 'reg') {
	include('registrate.php');

}

if($a == 'shop') {

	include('shop-classes.php');
	include('product-list.php');

}

if($a == 'modify') {

	include('deliveryModify.php');

}

if($a == 'discount') {

	include('discountBasket.php');

}

if($a == 'profile') {

	include('profile.php');
	include('profile-discount.php');

}

if($a == 'specCoupon') {

	include('adminSpecificCoupon.php');

}

if($a == 'orderSpecs') {

	include('orderSpecs.php');

}

if(isset($c)) {

	include('basket.php');

}

if($a == 'infoChange') {

	include('userInfoChange.php');

}

if($a == 'basket') {

	include('basket-content.php');

}

if($a == 'details') {

	include('product-details.php');

}

if($a == 'search') {

	include('search.php');

}

if($a == '') {

	include('profile-discount.php');

	if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {

	}

}

if($a == 'userCoupon') {

	include('userCoupon.php');

}

if($a == 'admin' && $_SESSION['admin'] == 1) {

	include('adminMenu.php');

	if($a == 'admin' && $b == 'file') {

		include('adminFile.php');

	}

	if($a == 'admin' && $b == 'Item') {

		include('adminOneItemUpload.php');

	}

	if($a == 'admin' && $b == 'Groups') {

		include('adminOneArticleGroup.php');

	}

	if($a == 'admin' && $b == 'User') {

		include('adminUserManagement.php');

	}

	if($a == 'admin' && $b == 'Usermodify') {

		include('userModify.php');

	}

	if($a == 'admin' && $b == 'Coupon' ) {

		include('adminCoupon.php');

		}

} elseif($a == 'admin' && $_SESSION['admin'] == 0) {

	echo '  <script type="text/javascript">
					alert ("Nincs admin jogosultsagod!");
					window.open ("index.php?", "_self");
 					</script> ';

}

echo 'HAHAHA';

echo 'AnotherOneThingInThisPHP';

?>

</body>
</html>