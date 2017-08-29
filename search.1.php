<?php

	 $searchword = filter_input(INPUT_POST, 'search'); //alma szilva
	 $searchword = str_replace(" ","%",$searchword);// alma%szilva
	 $check = filter_input(INPUT_POST, 'checkbox');
	 $email = $_SESSION['emailcim'];

	 if(isset($check)){

	 	$query = "SELECT `targy`,`szoveg`,`datum`,`emailcim` FROM forum WHERE emailcim ='$email' AND( szoveg LIKE '%$searchword%' OR targy LIKE '%$searchword%')";

	 } else {

 		$query = "SELECT `targy`,`szoveg`,`datum`,`emailcim` FROM forum WHERE szoveg LIKE '%$searchword%' OR targy LIKE '%$searchword%'";

 	}

 	$sqlw = mysqli_query($conn, $query);

 	if(mysqli_num_rows($sqlw) == 0){

 		echo "Nincs megjeleníthető post!";

 	}

 	while ($rows = mysqli_fetch_assoc($sqlw)) {
 		
 		echo '<table border="0"; width="800" align ="center"">
				<tr>';
				echo '<td align= "left" style="padding: 5px; background-color: rgba(0,0,0,0.6);"><strong>'.$rows['targy'].'</strong> ('.$rows['emailcim'].')</td>';
				echo '<td width="200" align= "right" style="padding: 5px; background-color: rgba(0,0,0,0.7);">'.$rows['datum'].'</td></tr><tr>';
				echo '<td colspan= "2" align= "left" style="padding: 5px; background-color: rgba(255,255,255,0.3);"><font color= "white">'.$rows['szoveg'].'</font><br><br>
					  </td></tr></table><br>
					  </div>';
 	}

?>