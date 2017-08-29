<?php

//$rows = basketContent();

echo '
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Webshop</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
       <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Products<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">';

          menuGroups();
            
  echo'</ul>
        </li>';
      if(isset($_SESSION['in'])) {
        echo '<li><a href="index.php?a=profile">Profile</a></li>';
      }
      echo '</ul>
      <form class="navbar-form navbar-left" role="search" action="index.php?a=search" method="post">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search" name="search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
      <li><a href="index.php?a=basket&b=1">
        <ul class="nav nav-pills">';
        if(isset($_SESSION['email'])) { echo'<li class="active"><span class="badge">'.$_SESSION['email'].'</span></li>';}
        if(isset($_SESSION['inbasket'])) { echo'<li class="active">Basket<span class="badge">'.$_SESSION['inbasket'].'</span></li>';}
                                    else {echo'<li class="active">Basket</li>';}
      echo'</ul>
    </a></li>
    <li><a href="index.php?a=userCoupon">Coupons</a></li>';
   // var_dump($_SESSION);
      if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
        echo '<li><a href="index.php?a=admin">MyAdmin</a></li>';
      }
      if(isset($_SESSION['in'])) {
        echo '<li><a href="index.php?a=logout">Logout</a></li>';
      } else {
        echo '<li><a href="index.php?a=login">Login</a></li>';
      }
       echo '
      </ul>
    </div>
  </div>
</nav>
';

function menuGroups() {
  global $conn;

  $query = "SELECT * FROM cikkcsoportkod WHERE szulo_ID = 0 ORDER BY mn_HU";
  $sql = mysqli_query($conn, $query);
  

  while($row = mysqli_fetch_assoc($sql)) {
    $query1 = "SELECT * FROM cikktorzs WHERE cikkcsoportkod = ".$row['ID'];
    $sql1 = mysqli_query($conn, $query1);
    $num = mysqli_num_rows($sql1);

           echo'<li>';
           if($num > 0){echo'<a href="index.php?a=shop&b='.$row['ID'].'">';}
           
           echo $row['mn_HU'];
           
           if($num > 0){echo'</a>';}
           
           echo'</li>';
              
           groupItems($row['ID']);   
      }
}

function groupItems($b) {
  global $conn;

  $query = "SELECT * FROM cikkcsoportkod WHERE szulo_ID = $b";
  $sql = mysqli_query($conn, $query);

  if(mysqli_num_rows($sql) > 0) {
   while($rows = mysqli_fetch_assoc($sql)) {
    $query1 = "SELECT * FROM cikktorzs WHERE cikkcsoportkod = ".$rows['ID'];
    $sql1 = mysqli_query($conn, $query1);
    $num = mysqli_num_rows($sql1);
    
      echo'<ul><li>';
      
         if($num > 0){echo'<a href="index.php?a=shop&b='.$rows['ID'].'">';}
          
          echo $rows['mn_HU'];
          
          echo'</a></li></ul>';

    }
  }
}

// function basketContent() {

//   global $conn;

//     if(isset($_SESSION['in'])) {

//       $owner = $_SESSION['userid'];

//     } else {

//       $owner = session_id();

//     }

//   $query = "SELECT * FROM basket WHERE KosarTulajdonosa = '$owner'";
//   $sql3 = mysqli_query($conn, $query);
//   $rows = mysqli_num_rows($sql3);

//   return $rows;

// }
?>

