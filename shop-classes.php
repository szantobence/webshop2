<?php
    
  if($b == 'shoes'){

    shoesQuery();

  }

  if($b == 'textil') {

    textilQuery();

  }

  if($b == 'hardware') {

    hardwareQuery(); 

  }

function shoesQuery() {
  global $conn;

  $query = "SELECT * FROM cikktorzs WHERE cikk_tipus = 'Shoes'";
  $sql = mysqli_query($conn, $query);

  while($rows = mysqli_fetch_assoc($sql)) {

    echo'
    <table class="table table-striped table-hover ">
      <tbody>
        <tr>
          <td>'.$rows['megnevzes'].'</td>
          <td><a href="index.php?a=shop&c='.$rows['ID'].'">  Buy</td>
        </tr>
      </tbody>
    </table>';

    echo $rows['megnevzes'].'<a href="index.php?a=shop&c='.$rows['ID'].'">  Buy</br>';

  }
}

function textilQuery() {
  global $conn;

  $query = "SELECT * FROM cikktorzs WHERE cikk_tipus = 'Textil'";
  $sql = mysqli_query($conn, $query);

  while($rows = mysqli_fetch_assoc($sql)) {

    echo $rows['megnevzes'].'</br>';

  }
}

function hardwareQuery() {
  global $conn;

  $query = "SELECT * FROM cikktorzs WHERE cikk_tipus = 'Hardware'";
  $sql = mysqli_query($conn, $query);

  while($rows = mysqli_fetch_assoc($sql)) {

    echo $rows['megnevzes'].'</br>';

  }
}




?>