<?php

  if(filter_input(INPUT_POST, 'submit')) {

    $addedname = filter_input(INPUT_POST, 'name');
    $city = filter_input(INPUT_POST, 'city');
    $zipcode = filter_input(INPUT_POST, 'zipcode');
    $housenumber = filter_input(INPUT_POST, 'housenumber');
    $account = filter_input(INPUT_POST, 'accountnumber');
    $street = filter_input(INPUT_POST, 'streetname');
    $floor = filter_input(INPUT_POST, 'floor');
    $door = filter_input(INPUT_POST, 'door');

    $email = $_SESSION['email'];

    $query = "UPDATE users SET Nev = '$addedname', Varos = '$city', iranyitoszam = '$zipcode', hazszam = '$housenumber', szamlaszam = '$account', Utca='$street', emelet='$floor', ajto = '$door' WHERE email ='$email'";
    mysqli_query($conn, $query);

  }

  header("Refresh:0; url=index.php?a=profile");

?>