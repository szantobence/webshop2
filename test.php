<!DOCTYPE html>
<html>
<head>

  <script src="code.jquery.min.js"></script>
	<script src="jsbootswatch.min.js"></script>
	<link rel="stylesheet" type="text/css" href="bootswatchcss.css">

<title></title>
</head>
<body>

<?php

include('connect.php');

test();

$array = query();

//queryGiveTest($array);

$array2 = formTest();

echo '</br></br>';

echo $array2[0];
  echo $array2[1];

function test() {

  echo '<form action="test.php" method="post" id="kaka">
  
          <input type="Text" name="tester" method="post">
          <input type="submit" name="submit">

          <input type="radio" name="gender" value="male" checked> Male<br>
          <input type="radio" name="gender" value="female"> Female<br>
          <input type="radio" name="gender" value="other"> Other  
        </form>';

}

function query() {
  global $conn;

  $query = "SELECT * FROM users WHERE email = 'szantobence@gmail.com'";
  $sql = mysqli_query($conn, $query);

  $rows = mysqli_fetch_assoc($sql);

  return $rows;

}

function queryGiveTest($array) {

  echo $array['Nev'];
  echo $array['email'];

}

function formTest() {

  if(filter_input(INPUT_POST, 'submit')) {

  $testet = filter_input(INPUT_POST, 'tester');
  $gender = filter_input(INPUT_POST, 'gender');

  echo $gender;
  echo $testet;

 return array($gender, $testet);

  }
}

?>

</body>
</html>