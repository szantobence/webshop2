<?php

	$searchword = filter_input(INPUT_POST, 'search'); //alma szilva
	$searchword = str_replace(" ","%",$searchword);// alma%szilva
	$picture = '';

	$query = "SELECT * FROM cikktorzs WHERE ( cikkszam LIKE '%$searchword%' OR megnevzes LIKE '%$searchword%' OR cikk_tipus LIKE '%$searchword%' OR szin LIKE '%$searchword%' OR meret LIKE '%$searchword%')";

 	$sqlw = mysqli_query($conn, $query);

 	if(mysqli_num_rows($sqlw) == 0){

		echo'
			<div class="col-lg-4"></div>
			<div class="alert alert-dismissible alert-warning col-lg-4" align="center">
  		<button type="button" class="close" data-dismiss="alert">&times;</button>
  		<p>Nincs megjelenitheto termek!</p>
			</div>';
 		
 	}

 	while ($rows = mysqli_fetch_assoc($sqlw)) {

		 $picture = 'pictures/'.$rows['cikkszam'].'_1.'.'jpg';

		if(file_exists($picture)) {

      $pictureExists = 1;

    } else {

      $picture = 'pictures/nopicture.jpg';

    }

		 echo'
    	<table class="table table-striped table-hover">
    	<thead>
        <tr>
          <th width="20%"></th>
          <th width="20%">Megnevezes</th>
          <th width="20%">Cikkszam</th>
          <th width="20%">Ar</th>
          <th width="20%">Leiras</th>
          <th width="20%"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <form class="form-horizontal" action="index.php?a=shop&c='.$rows['ID'].'&f='.$rows['cikkszam'].'" method="post">
          <fieldset>
          <td width="14%"><a href="'.$picture.'"><img src="'.$picture.'" alt="Mountain View" style="width:240px;"></a></td>
          <td width="14%">'.$rows['megnevzes'].'</a></td>
          <td width="14%">'.'('.$rows['cikkszam'].')'.'</td>
          <td width="14%">'.$rows['kiskerar'].'</td>
          <td width="14%">'.$rows['informacio'].'</td>
          </fieldset>
          </form>
        </tr>
      </tbody>
    </table>
    ';

 	}

?>