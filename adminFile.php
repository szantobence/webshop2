<?php

//include ('connect.php');
if(filter_input(INPUT_POST, 'submit')){
run();
}

function run() {

  $folder ="Uploads/" ;
  $file = $_FILES['fileUpload']['name'];
  $filetemp = $_FILES['fileUpload']['tmp_name'];
  $reach = $folder.$file;
  $filename = '';

  if(filter_input(INPUT_POST, 'submit')){
    if(move_uploaded_file($filetemp, $reach)) {
      echo 'sikeres feltöltés<br>';
      echo "$file<br>";

         global $conn;
      // $delete = "TRUNCATE cikktorzsdata";
      // mysqli_query($conn, $delete);

      $filename = 'c:/xampp/htdocs/00/webshop/Uploads/'.$file;
      $content = file_get_contents($filename);
      
    $lines = processContent($content);

    processLines($lines);

    } else {
      echo 'sikertelen fájlfeltöltés, hiba: ';
    }
  }
}

function processContent($content) {
  $newLineChar = chr(10);
  $lines = explode($newLineChar, $content);

  return $lines;
}

function processLines($lines) {
  $f = 0;
  $tabChar = chr(9);
  foreach($lines as $line) {
    $f++;
    if($f > 1) {
      $rowData = explode($tabChar, $line);
      processOneRow($rowData);
    }
  }
}

function processOneRow($rowData) {
  global $conn;
  // badInputFilter($rowData);

  if(count($rowData) != 1 /*&& is_numeric($rowData[0]) == TRUE */)  {

    // if($rowData[0] == '') $rowData[0] = 0;
    // if(is_string($rowData[1]) == FALSE) $rowData[1] = '';
    // if(is_string($rowData[2]) == FALSE) $rowData[2] = '';
    // if(is_numeric($rowData[3]) == FALSE) $rowData[3] = '';
    // if(is_numeric($rowData[4]) == FALSE) $rowData[4] = ''; 
    // if(is_numeric($rowData[5]) == FALSE) $rowData[5] = '';
    // if(empty($rowData[6]) == TRUE) $rowData[6] = 'sth';
    
    $command1 = "INSERT INTO `cikktorzs` (cikkszam, megnevzes, cikk_tipus, szin, meret, suly, ean, cikkcsoportkod, nagykerar,
                                         kiskerar, specar, darab, termekakcio, informacio, afa, kuponkod)
                  VALUES ('$rowData[0]','$rowData[1]','$rowData[2]','$rowData[3]','$rowData[4]','$rowData[5]','$rowData[6]','$rowData[7]','$rowData[8]','$rowData[9]','$rowData[10]',
                          '$rowData[11]','$rowData[12]','$rowData[13]','$rowData[14]','$rowData[15]')";
    echo $command1;

    mysqli_query($conn, $command1) or die (mysqli_error());

  } else { 
    echo 'nincs elegendo adat!<br/>';
  }
}

function badInputFilter($rowData) {

  if($rowData[0] == '') $rowData[0] = 0;
  if(is_string($rowData[1]) == FALSE) $rowData[1] = '';
  if(is_string($rowData[2]) == FALSE) $rowData[2] = '';
  if(is_numeric($rowData[3]) == FALSE) $rowData[3] = '';
  if(is_numeric($rowData[4]) == FALSE) $rowData[4] = ''; 
  if(is_numeric($rowData[5]) == FALSE) $rowData[5] = '';
  if(empty($rowData[6]) == TRUE) $rowData[6] = 'sth';

  var_dump($rowData);

  return $rowData;

}

echo '
<div>
<form action="index.php?a=admin&b=file" method="post" enctype="multipart/form-data">
  <input type="file" name="fileUpload" id="fileUpload">
  <input type="submit" value="Upload File" name="submit">
</form>
</div>
';

?>