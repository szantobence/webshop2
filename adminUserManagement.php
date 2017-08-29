<?php

echo'

<div class="container-fluid" style="margin: auto; width: 400px">
<form class="navbar-form navbar-left" role="search" action="index.php?a=admin&b=User" method="post">
    <div class="form-group">
      <input type="text" class="form-control" placeholder="Search" name="userSearch">
    </div>
  <div class="form-group">
    <div class="col-lg-10 col-lg-offset-0">
      <input type="submit" name="submit" class="btn btn-primary">
    </div>
  </div>
</form>
</div>

';

if(filter_input(INPUT_POST, 'submit')) {

  $searchword = filter_input(INPUT_POST, 'userSearch'); //alma szilva
  $searchword = str_replace(" ","%",$searchword);// alma%szilva
  $picture = '';

  $query = "SELECT * FROM users WHERE ( Nev LIKE '%$searchword%' OR email LIKE '%$searchword%' OR Varos LIKE '%$searchword%' OR iranyitoszam LIKE '%$searchword%' OR Utca LIKE '%$searchword%')";

  $sqlw = mysqli_query($conn, $query);

  echo '
  <table class="table table-striped table-hover " border="1">
      <thead>
        <tr class="success">
          <th width="14%">Nev</th>
          <th width="14%">Email</th>
          <th width="14%">Varos</th>
          <th width="14%">Szuletesi ido</th>
          <th width="14%">Szuletesi hely</th>
          <th width="14%"></th>
          <th width="14%"></th>
        </tr>
      </thead>
      </table>';

  while($rows = mysqli_fetch_assoc($sqlw)) {

    echo'
      <table class="table table-striped table-hover " border="1">
      <tbody>
        <tr class="success">
          <td width="14%">'.$rows['Nev'].'</td>
          <td width="14%">'.$rows['email'].'</td>
          <td width="14%">'.$rows['Varos'].'</td>
          <td width="14%">'.$rows['szuletesi_ido'].'</td>
          <td width="14%">'.$rows['szuletesi_hely'].'</td>
          <td width="14%"><a href="index.php?a=admin&b=Usermodify&f='.$rows['id'].'">Modositas</a></td>
          <td width="14%"><a href="index.php?a=admin&b=User&delete='.$rows['id'].'">Torles</a></td>
        </tr>
      </tbody>
    </table> 
    ';

  }
} else {

  if(isset($delete)) {

    $query = "DELETE FROM users WHERE id = '$delete'";
    mysqli_query($conn, $query);

  }

  $query = 'SELECT * FROM users';
  $sql = mysqli_query($conn, $query);

  echo '
  <table class="table table-striped table-hover " border="1">
      <thead>
        <tr class="success">
          <th width="14%">Nev</th>
          <th width="14%">Email</th>
          <th width="14%">Varos</th>
          <th width="14%">Szuletesi ido</th>
          <th width="14%">Szuletesi hely</th>
          <th width="14%"></th>
          <th width="14%"></th>
        </tr>
      </thead>
      </table>';

  while($rows = mysqli_fetch_assoc($sql)) {

    echo'
      <table class="table table-striped table-hover " border="1">
      <tbody>
        <tr class="success">
          <td width="14%">'.$rows['Nev'].'</td>
          <td width="14%">'.$rows['email'].'</td>
          <td width="14%">'.$rows['Varos'].'</td>
          <td width="14%">'.$rows['szuletesi_ido'].'</td>
          <td width="14%">'.$rows['szuletesi_hely'].'</td>
          <td width="14%"><a href="index.php?a=admin&b=Usermodify&f='.$rows['id'].'">Modositas</a></td>
          <td width="14%"><a href="index.php?a=admin&b=User&delete='.$rows['id'].'">Torles</a></td>
        </tr>
      </tbody>
    </table> 
    ';

  }
}

?>