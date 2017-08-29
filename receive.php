<?php

    $error = '';
  if(filter_input(INPUT_POST, 'submit') && $a == 'loginproc') {
    
    $email = filter_input(INPUT_POST, 'email');
    $pass = filter_input(INPUT_POST, 'pass');

    $pass = sha1($pass);

    $query = "SELECT * FROM users WHERE email = '$email' AND jelszo = '$pass'";
    $sql = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($sql); 

    if(mysqli_num_rows($sql) == 1 && $row['aktivitas'] == 'igen'){
      echo"YAY";
      header("Refresh:0 , url=index.php?");
      $_SESSION['in'] = TRUE;
      $_SESSION['email'] = $row['email'];
      $_SESSION['pass'] = $row['jelszo'];
      $_SESSION['userid'] = $row['id'];
      $_SESSION['admin'] = 0;
      $_SESSION['inbasket'] = '';

      if($row['AdminE'] > 0) {

        $_SESSION['admin'] = 1;

      }

      itemUpdateAfterLogin();

    } else {
      $a = 'login';
      $error = 1;
    }
}

function itemUpdateAfterLogin() {
  global $conn;
  
  $name = $_SESSION['email'];
  $sessionName = session_id();

  $query2 = "UPDATE basket SET `KosarTulajdonosa` ='$name' WHERE `KosarTulajdonosa` ='$sessionName'";
  mysqli_query($conn, $query2);

}
?>