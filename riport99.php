<?php

if ($a=="riport99")
	{

$jobWorkers = filter_input(INPUT_GET, 'jobWorkers');
$fejlesztes = filter_input(INPUT_GET, 'fejlesztes');

	if (($jogosultsag=="50") OR ($jogosultsag=="57"))
		{

		$dmev=$_POST['datummodev'];
		$dmho=$_POST['datummodhonap'];

		if ($dmev=="") $dmev=$_GET['dmev'];
		if ($dmho=="") $dmho=$_GET['dmho'];

		if ($dmev=="") $dmev=$y;
		if ($dmho=="") $dmho=$m;

		$datet=date("t", strtotime("$dmev-$dmho-01"));

		$cid=$_GET['cid'];					// kapott cegid
		$szid=$_GET['szid'];				// kapott szerzodesid
		$aid=$_GET['aid'];					// kapott alkalmazasid
		$jidg=$_GET['jidg'];				// kapott jobid


		print ($riportmenu.'


			<form action="?lang='.$lang.'&a=riport99" method="post">
			<font size="1"><b>'.$szoveg[189].': 
			<select class="input" name="datummodev" id="datummodev">
			<option value="'.$ly.'"');
		if ($dmev==$ly) print (' selected');
		print ('>'.$ly.'</option> 
			<option value="'.$y.'"');
		if ($dmev==$y) print (' selected');
		print ('>'.$y.'</option>
			</select>

			<select class="input" name="datummodhonap" id="datummodhonap">
			');
		for ($honap = 1; $honap <= 12; $honap++)
			{
			$honapszam=date("m", strtotime("$y-$honap-01"));
			$honapszoveg=date("F", strtotime("$y-$honap-01"));
			print ('<option value="'.$honapszam.'"');
		if ($dmho==$honapszam) print (' selected');
		print ('>'.$honapszoveg.'</option> 
				');
			}
		$dmhosz=date("F", strtotime("$y-$dmho-01"));

		print ('
			</select>
			
			<input type="submit" value="'.$szoveg[360].'" class="input" onclick="this.disabled=true;this.value=');
		print ("'".$szoveg[562]."'");
		print (';this.form.submit();">
			</form>
			<br /><br /><br />
			');

		$sorok=0;

		if(isset($_POST['fejlesztes'])){

												$fejlesztes = 1;

											} else {

												$fejlesztes = 0;

											}

		// ----------------------------========================== cégkockák feltöltése =========================--------------------------\\

		$lekerdezcegek = mysql_query ("	SELECT companies.cegid, companies.cegnev, contracts.nev, applications.appteljesnev, jobs.id, 
												jobs.jobname, users.name, users.active, workhours.whdate, workhours.whtype, workhours.hours, 
												mailrights.jog
									FROM workhours
									INNER JOIN jobs ON workhours.jobid=jobs.id
									INNER JOIN applications ON applications.id=jobs.jobappl
									INNER JOIN contracts ON contracts.id=applications.szerzodesszam
									INNER JOIN companies ON companies.cegid=contracts.cegid
									INNER JOIN users ON users.id=workhours.userid
									INNER JOIN mailrights ON mailrights.mailcim=users.mail
									WHERE ((jog!='50') AND (whtype IS NULL) AND (hours!='0.0') AND (whdate LIKE '$dmev$dmho%'))
									GROUP BY cegnev
									ORDER BY cegnev
									");							
		while ($sorcegek = mysql_fetch_array ($lekerdezcegek)) 
			{
			$sorok++;
			$cegidrdb=$sorcegek[cegid];
			$cegkocka[$sorok][0]=$cegidrdb;
			}

			
		for ($sqlsorok = 1; $sqlsorok <= $sorok; $sqlsorok++) 
			{ 
			$lekerdezcegek = mysql_query ("	SELECT companies.cegid, companies.cegnev, contracts.nev, applications.appteljesnev, jobs.id, 
												jobs.jobname, users.name, users.active, workhours.whdate, workhours.whtype, workhours.hours, 
												mailrights.jog
									FROM workhours
									INNER JOIN jobs ON workhours.jobid=jobs.id
									INNER JOIN applications ON applications.id=jobs.jobappl
									INNER JOIN contracts ON contracts.id=applications.szerzodesszam
									INNER JOIN companies ON companies.cegid=contracts.cegid
									INNER JOIN users ON users.id=workhours.userid
									INNER JOIN mailrights ON mailrights.mailcim=users.mail
									WHERE ((jog!='50') AND (whtype IS NULL) AND (hours!='0.0') AND (whdate LIKE '$dmev$dmho%'))
									ORDER BY whdate
									") or die(mysql_error());
			while ($sorcegek = mysql_fetch_array ($lekerdezcegek)) 
			  {
			  $whcegidrdb=$sorcegek[cegid];
			  $whdaterdb=$sorcegek[whdate];
			  $whdaterdbs=substr ($whdaterdb, 6, 2);
			  $whdaterdbsi=(int)$whdaterdbs;
			  $hoursrdb=$sorcegek[hours];
			  if ($whcegidrdb==$cegkocka[$sqlsorok][0]) 
				{
				$cegkocka[$sqlsorok][$whdaterdbsi]=$cegkocka[$sqlsorok][$whdaterdbsi]+$hoursrdb;
				}
			  }
			}


		// ----------------------------========================== szerződéskockák feltöltése =========================--------------------------\\

	if ($cid!="")
		{
		$szerzsorok=0;
		$lekerdezszerzodesek = mysql_query ("	SELECT companies.cegid ccegid, companies.cegnev, contracts.nev cnev, contracts.id contid, applications.appteljesnev, jobs.id, 
												jobs.jobname, users.name, users.active, workhours.whdate, workhours.whtype, workhours.hours, 
												mailrights.jog
												FROM workhours
												INNER JOIN jobs ON workhours.jobid=jobs.id
												INNER JOIN applications ON applications.id=jobs.jobappl
												INNER JOIN contracts ON contracts.id=applications.szerzodesszam
												INNER JOIN companies ON companies.cegid=contracts.cegid
												INNER JOIN users ON users.id=workhours.userid
												INNER JOIN mailrights ON mailrights.mailcim=users.mail
												WHERE ((jog!='50') AND (whtype IS NULL) AND (hours!='0.0') AND (whdate LIKE '$dmev$dmho%'))
												GROUP BY cnev
												ORDER BY cnev
											") or die(mysql_error());							
		while ($sorszerzodesek = mysql_fetch_array ($lekerdezszerzodesek)) 
			{
			$szerzidrdb=$sorszerzodesek[contid];
			$szerznevrdb=$sorszerzodesek[cnev];
			$cegididrdb=$sorszerzodesek[ccegid];
			if ($cid==$cegididrdb)
				{
				$szerzsorok++;
				$szerzodeskocka[$szerzsorok][0]=$szerzidrdb;
				}
			}

			
		for ($sqlsorok = 1; $sqlsorok <= $szerzsorok; $sqlsorok++) 
			{ 
			$lekerdezszerzodesek = mysql_query ("	SELECT companies.cegid ccegid, companies.cegnev, contracts.nev cnev, contracts.id contid, applications.appteljesnev, jobs.id, 
												jobs.jobname, users.name, users.active, workhours.whdate, workhours.whtype, workhours.hours, 
												mailrights.jog
												FROM workhours
												INNER JOIN jobs ON workhours.jobid=jobs.id
												INNER JOIN applications ON applications.id=jobs.jobappl
												INNER JOIN contracts ON contracts.id=applications.szerzodesszam
												INNER JOIN companies ON companies.cegid=contracts.cegid
												INNER JOIN users ON users.id=workhours.userid
												INNER JOIN mailrights ON mailrights.mailcim=users.mail
												WHERE ((jog!='50') AND (whtype IS NULL) AND (hours!='0.0') AND (whdate LIKE '$dmev$dmho%'))
												ORDER BY whdate
											") or die(mysql_error());		
			while ($sorszerzodesek = mysql_fetch_array ($lekerdezszerzodesek)) 
			  {
			  $qweszerzjobid==$sorszerzodesek[jobid];
			  //print (' jid:'.$qweszerzjobid);
			  $whszerzidrdb=$sorszerzodesek[contid];
			  $whdaterdb=$sorszerzodesek[whdate];
			  $whdaterdbs=substr ($whdaterdb, 6, 2);
			  $whdaterdbsi=(int)$whdaterdbs;
			  $hoursrdb=$sorszerzodesek[hours];
			  if ($whszerzidrdb==$szerzodeskocka[$sqlsorok][0]) 
				{
				$szerzodeskocka[$sqlsorok][$whdaterdbsi]=$szerzodeskocka[$sqlsorok][$whdaterdbsi]+$hoursrdb;
				}
			  }
			}

		}

		// ----------------------------========================== jobkockák feltöltése =========================--------------------------\\

	if ($szid!="")
		{
		$jobsorok=0;

/*
		$r5query1="	SELECT applications.szerzodesszam, jobs.jobappl, jobs.jobname, mailrights.jog, workhours.*
					FROM workhours
					INNER JOIN jobs ON jobs.id=workhours.jobid
					INNER JOIN applications ON applications.id=jobs.jobappl
					INNER JOIN users ON users.id=workhours.userid
					INNER JOIN mailrights ON mailrights.mailcim=users.mail
					WHERE ((szerzodesszam='$szid') AND (jog!='50') AND (whtype IS NULL) AND (hours!='0.0') AND (whdate LIKE '$dmev$dmho%'))
					GROUP BY jobid
					ORDER BY changepipa DESC, projectpipa DESC, jobname";
*/

// új query alfeladatok listázásának kiszűrése miatt

		$r5query1="	SELECT applications.szerzodesszam, jobs.jobappl, jobs.jobname, mailrights.jog, workhours.*
					FROM workhours
					INNER JOIN jobs ON jobs.id=workhours.jobid
					INNER JOIN applications ON applications.id=jobs.jobappl
					INNER JOIN users ON users.id=workhours.userid
					INNER JOIN mailrights ON mailrights.mailcim=users.mail
					WHERE ((szerzodesszam='$szid') AND (jog!='50') AND (whtype IS NULL) AND (hours!='0.0') AND (whdate LIKE '$dmev$dmho%')) AND (szulofeladatid IS NULL OR szulofeladatid='' OR szulofeladatid<1)
					GROUP BY jobid
					ORDER BY changepipa DESC, projectpipa DESC, jobname";


//echo 'r5q:'.$r5query1;

/*

SELECT applications.szerzodesszam, jobs.jobappl, jobs.jobname, mailrights.jog, workhours.* 
FROM workhours 
INNER JOIN jobs ON jobs.id=workhours.jobid 
INNER JOIN applications ON applications.id=jobs.jobappl 
INNER JOIN users ON users.id=workhours.userid 
INNER JOIN mailrights ON mailrights.mailcim=users.mail 
WHERE ((szerzodesszam='69') AND (jog!='50') AND (whtype IS NULL) AND (hours!='0.0') AND (whdate LIKE '201704%')) AND (szulofeladatid IS NULL OR szulofeladatid='' OR szulofeladatid<1)
GROUP BY jobid ORDER BY changepipa DESC, projectpipa DESC, jobname

*/

//		echo '<br>r5query1:'.$r5query1;

		$lekerdezjob = mysql_query ($r5query1) or die(mysql_error());

										// ORDER BY changepipa DESC, jobname

		while ($sorjob = mysql_fetch_array ($lekerdezjob)) 
			{
			$jobidrdb=$sorjob[jobid];
			$jobnevrdb=$sorjob[jobname];
			$szerzidrdb=$sorjob[contid];
			$jobsorok++;
			$jobkocka[$jobsorok][0]=$jobidrdb;
			//echo "<br>jobkocka[jobsorok:$jobsorok][0]=$jobidrdb";
			}

			
		for ($sqlsorok = 1; $sqlsorok <= $jobsorok; $sqlsorok++) 
			{ 
			$lekerdezjob = mysql_query ("	SELECT companies.cegid ccegid, companies.cegnev, contracts.nev cnev, contracts.id contid, 
												applications.id appid, applications.appteljesnev, jobs.id jobiid, jobs.jobname, users.name, users.active, workhours.whdate, 
												workhours.whtype, workhours.hours, mailrights.jog
												FROM workhours
												INNER JOIN jobs ON workhours.jobid=jobs.id
												INNER JOIN applications ON applications.id=jobs.jobappl
												INNER JOIN contracts ON contracts.id=applications.szerzodesszam
												INNER JOIN companies ON companies.cegid=contracts.cegid
												INNER JOIN users ON users.id=workhours.userid
												INNER JOIN mailrights ON mailrights.mailcim=users.mail
												WHERE ((jog!='50') AND (whtype IS NULL) AND (hours!='0.0') AND (whdate LIKE '$dmev$dmho%'))
												ORDER BY whdate
											") or die(mysql_error());		



			while ($sorjob = mysql_fetch_array ($lekerdezjob)) 
			  {
			  $qwealkjobid==$sorjob[jobid];
			  $whjobidrdb=$sorjob[jobiid];
			  $whdaterdb=$sorjob[whdate];
			  $whdaterdbs=substr ($whdaterdb, 6, 2);
			  $whdaterdbsi=(int)$whdaterdbs;
			  $hoursrdb=$sorjob[hours];
				
			  if ($whjobidrdb==$jobkocka[$sqlsorok][0]) 
				{
				$jobkocka[$sqlsorok][$whdaterdbsi]=$jobkocka[$sqlsorok][$whdaterdbsi]+$hoursrdb;
				//echo "<br>jobkocka[sqlsorok:$sqlsorok][whdaterdbsi:$whdaterdbsi]=jobkocka[sqlsorok:$sqlsorok][whdaterdbsi:$whdaterdbsi]+$hoursrdb";

				//echo "---- ";
				//echo $jobkocka[$sqlsorok][0];

// alfeladat óráinak hozzáadása

				$r5query_alfeladat="	SELECT id FROM jobs WHERE szulofeladatid='".$jobkocka[$sqlsorok][0]."'";
				//echo '<br>((('.$r5query_alfeladat.')))';
				$r5result_alfeladat=mysql_query($r5query_alfeladat);
				while ($r5row=mysql_fetch_assoc($r5result_alfeladat)) 
					{
						//echo '<br>-----------------------------------------------------------'.$r5row['id'];
						$alfeladatok[]=$r5row['id'];
					}


				}
			  }
			

			if (isset($alfeladatok)) {
				//echo 'alfeladatok:';
				sort($alfeladatok);
				$alfeladatok=array_unique($alfeladatok);
				foreach ($alfeladatok as $key => $value) {
					//echo '<br>====== '.$value.' (sqlsorok:'.$sqlsorok.')';

					$r5query2 = "	SELECT SUM(hours) AS szumora, RIGHT(whdate,2) AS szumdatum 
									FROM workhours 
									WHERE (jobid='$value') AND (whtype IS NULL) AND (hours!='0.0') AND (whdate LIKE '$dmev$dmho%') 
									GROUP BY whdate";
					//echo '<br>qry:'.$r5query2;
					$r5result2=mysql_query($r5query2);
					while ($r5row2=mysql_fetch_assoc($r5result2)) {
						//echo '<br>nap:'.$r5row2['szumdatum'].' ora:'.$r5row2['szumora'];
						$whdaterdbsi=round($r5row2['szumdatum']);
						$hoursrdb=$r5row2['szumora'];
						//echo 'whdaterdbsi:'.$whdaterdbsi;
						$jobkocka[$sqlsorok][$whdaterdbsi]=$jobkocka[$sqlsorok][$whdaterdbsi]+$hoursrdb;
					}


				}
				unset($alfeladatok);

// alfeladat óráinak hozzáadása vége			
			
			}

			}

		}
		// ----------------------------========================== userkockák feltöltése =========================--------------------------\\

	if ($jidg!="")
		{
		$usrsorok=0;
		$lekerdezusr = mysql_query ("	SELECT jobs.jobappl, jobs.jobname, mailrights.jog, users.name, workhours.jobid, workhours.hours, 
												workhours.whdate, workhours.whtype, workhours.userid
										FROM workhours
										INNER JOIN jobs ON jobs.id=workhours.jobid
										INNER JOIN users ON users.id=workhours.userid
										INNER JOIN mailrights ON mailrights.mailcim=users.mail
										WHERE ((jobid='$jidg') AND (jog!='50') AND (whtype IS NULL) AND (hours!='0.0') AND (whdate LIKE '$dmev$dmho%'))
										GROUP BY name
										ORDER BY name
									") or die(mysql_error());



		while ($sorusr = mysql_fetch_array ($lekerdezusr)) 
			{
				
			$usridrdb=$sorusr[userid];
			$usrnevrdb=$sorusr[name];
			$jobidrdb=$sorusr[jobid];
			if ($jidg==$jobidrdb)
				{
				$usrsorok++;
				$usrkocka[$usrsorok][0]=$usridrdb;
				}
			}		
		for ($sqlsorok = 1; $sqlsorok <= $usrsorok; $sqlsorok++) 
			{ 

			$lekerdezusr = mysql_query ("	SELECT jobs.jobappl, jobs.jobname, mailrights.jog, users.name, workhours.jobid, workhours.hours, 
												workhours.whdate, workhours.whtype, workhours.userid
										FROM workhours
										INNER JOIN jobs ON jobs.id=workhours.jobid
										INNER JOIN users ON users.id=workhours.userid
										INNER JOIN mailrights ON mailrights.mailcim=users.mail
										WHERE ((jobid='$jidg') AND (jog!='50') AND (whtype IS NULL) AND (hours!='0.0') AND (whdate LIKE '$dmev$dmho%'))
										ORDER BY whdate
									") or die(mysql_error());


			while ($sorusr = mysql_fetch_array ($lekerdezusr)) 
			  {
			  $qwealkjobid==$sorusr[jobid];
			  $whusridrdb=$sorusr[userid];
			  $whdaterdb=$sorusr[whdate];
			  $whdaterdbs=substr ($whdaterdb, 6, 2);
			  $whdaterdbsi=(int)$whdaterdbs;
			  $hoursrdb=$sorusr[hours];
			  if ($whusridrdb==$usrkocka[$sqlsorok][0]) 
				{
				$usrkocka[$sqlsorok][$whdaterdbsi]=$usrkocka[$sqlsorok][$whdaterdbsi]+$hoursrdb;
				}
			  }
			}
		}

		// -------------------------------================================= fejléc =====================================-------------------------- \\

		$kiirkep='<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-width: 0"><tr>';
		$kiirprint='<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-width: 0"><tr>';		

		for ($cknap = 0; $cknap <= $datet; $cknap++)
	   		{
	   		$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
	   		if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }
	   		if ($cknap=="0")
	   			{
	   			$dmhosz=date("F", strtotime("$dmev-$dmho-01"));
	   			$kiirkep.='
	     		<td width="400" height="25" valign="center" align="center" bgcolor="#B8B8B6"><font size="1"><b><i>'.$dmev.' - '.$dmhosz.'
	     		</i></b></font></td>';
	   			$kiirprint.='
	     		<td width="400" height="25" valign="center" align="center"><font size="1"><b><i>'.$dmev.' - '.$dmhosz.'
	     		</i></b></font></td>';	     		
	   			}
	   			else
	   			{




	   			$naphatterszin="#B8B8B6";
	   		
	   			$knapwn=$cknap;
	   			if ($cknap<10) $knapwn='0'.$cknap;
	   			$sqlbasedtime=$dmev.'-'.$dmho.'-'.$knapwn;

	   			//print ("sqlbasedtime:$sqlbasedtime");

// unnepnap kieg.

	   			$szamlunnepnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='u'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlunnepnap++;
				}
				if ($szamlunnepnap!=0) $betuszin="#FF0000";

// unnepnap kieg. vege

// kotelezo szabi kieg.

	   			$szamlunnepnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='ksz'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlunnepnap++;
				}
				if ($szamlunnepnap!=0) $naphatterszin="#ff7a7a"; 

// kotelezo szabi kieg. vege

// kotelezo munkanap kieg.

	   			$szamlmnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='mn'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlmnap++;
				}
				if ($szamlmnap!=0) $betuszin="#00000";

// kotelezo munkanap kieg.

	   			if ($om[$knap]=="i") $naphatterszin="#C8DDC6"; 






	   			$kiirkep.='
	     		<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1" color="'.$betuszin.'"><b>
	     		<i>'.$cknap.'</i></b></font></td>';
	   			$kiirprint.='
	     		<td width="25" height="25" valign="center" align="center"><font size="1"><b>
	     		<i>'.$cknap.'</i></b></font></td>';	     		
	   			}
	   		}

	   	$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1"><b><i> </i></b></font></td></tr>';
	   	$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1"><b><i> </i></b></font></td></tr>';	   	


	   	// ------------------------------=================================== tartalom =============================------------------------------- \\
	   	$cegoszlopossz[$cknap]=0;
	   	for ($cegszaml = 1; $cegszaml <= $sorok; $cegszaml++)
	   		{
	   		$cegidtabl=$cegkocka[$cegszaml][0];
		// cégnév szerinti felsorolás
	   		$sorvegiossz=0;
	   		$kiirkep.='<tr>';
	   		$kiirprint.='<tr>';	   		
	   		for ($cknap = 0; $cknap <= $datet; $cknap++)
	   			{
	   			$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
	   			if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }	   			
	   			if ($cknap=="0")
	   				{
	   				$cegidtabl=$cegkocka[$cegszaml][0];
	   				$lekerdezcegnev = mysql_query ("SELECT * FROM companies WHERE cegid='$cegidtabl'");
	   				while ($sorceg = mysql_fetch_array ($lekerdezcegnev)) 
						{
	   					$cegnevtabl=$sorceg[cegnev];
	   					}

	   				$kiirkep.='
	   		  		<td width="400" height="25" valign="center" align="left" bgcolor="#C8C8C6" style="padding-left:8px">
	   		  		<a href="?lang='.$lang.'&a=riport99&dmev='.$dmev.'&dmho='.$dmho.'&cid='.$cegidtabl.'">
	   		  		<font size="1" color="#000000"><b>'.$cegnevtabl.'</a>
	     			</b></font></td>';
	   				$kiirprint.='
	   		  		<td width="400" height="25" valign="center" align="left" style="padding-left:8px">
	   		  		<font size="1"><b>'.$cegnevtabl.'</a></b></font></td>';
	   				}
	   				else
	   				{



	   			$naphatterszin="#C8C8C6";
	   		
	   			$knapwn=$cknap;
	   			if ($cknap<10) $knapwn='0'.$cknap;
	   			$sqlbasedtime=$dmev.'-'.$dmho.'-'.$knapwn;

	   			//print ("sqlbasedtime:$sqlbasedtime");

// unnepnap kieg.

	   			$szamlunnepnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='u'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlunnepnap++;
				}
				if ($szamlunnepnap!=0) $betuszin="#FF0000";

// unnepnap kieg. vege

// kotelezo szabi kieg.

	   			$szamlunnepnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='ksz'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlunnepnap++;
				}
				if ($szamlunnepnap!=0) $naphatterszin="#ff7a7a"; 

// kotelezo szabi kieg. vege

// kotelezo munkanap kieg.

	   			$szamlmnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='mn'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlmnap++;
				}
				if ($szamlmnap!=0) $betuszin="#00000";

// kotelezo munkanap kieg.

	   			if ($om[$knap]=="i") $naphatterszin="#C8DDC6"; 





	   				$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1" color="'.$betuszin.'">
	   					<b>'.$cegkocka[$cegszaml][$cknap].'</b></font></td>';
	   				$pcegkocka=str_replace(".", ",", $cegkocka[$cegszaml][$cknap]);
	   				$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
	   					<b>'.$pcegkocka.'</b></font></td>';	   					
	   				$cegoszlopossz[$cknap]=$cegoszlopossz[$cknap]+$cegkocka[$cegszaml][$cknap];
	   				$sorvegiossz=$sorvegiossz+$cegkocka[$cegszaml][$cknap];
	   				}
	   			}
	   		$sorvegiossz_nap=round($sorvegiossz/8);
			$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1"><b> 
			<i>'.$sorvegiossz.'</b><br>('.$sorvegiossz_nap.')</i></font></td></tr>'; // egyes cegek összes munkaorajat adja meg -----------------------------------------------------
			$psorvegiossz=str_replace(".", ",", $sorvegiossz);
			$psorvegiossz_nap=str_replace(".", ",", $sorvegiossz_nap);
			$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1"><b>
			<i>'.$psorvegiossz.'</b><br>('.$psorvegiossz_nap.')</i></font></td></tr>';			
   		// szerződéses részletezés
			if ($cid==$cegidtabl)
				{
	   			for ($szerzszaml = 1; $szerzszaml <= $szerzsorok; $szerzszaml++)
	   				{
	   				$szerzidtabl=$szerzodeskocka[$szerzszaml][0];
	   				$sorvegiossz=0;
	   				$kiirkep.='<tr>';
	   				$kiirprint.='<tr>';	   				
	   				for ($cknap = 0; $cknap <= $datet; $cknap++)
	   					{
	   					$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
	   					if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }	   			
	   					if ($cknap=="0")
	   						{
	   						$szerzidtabl=$szerzodeskocka[$szerzszaml][0];
	   						$lekerdezszerznev = mysql_query ("SELECT * FROM contracts WHERE id='$szerzidtabl'");
	   						while ($sorszerz = mysql_fetch_array ($lekerdezszerznev)) 
								{
	   							$szerznevtabl=$sorszerz[nev];
	   							}


	   						$kiirkep.='
	   		  				<td width="400" height="25" valign="center" align="left" bgcolor="#D8D8D6" style="padding-left:8px"> &nbsp;  
	   		  				<a href="?lang='.$lang.'&a=riport99&dmev='.$dmev.'&dmho='.$dmho.'&cid='.$cid.'&szid='.$szerzidtabl.'">
	   		  				<font size="1" color="#000000"><b>'.$szerznevtabl.'</a>
	     					</b></font></td>'; //Szerzodes neve -----------------------
	   						$kiirprint.='
	   		  				<td width="400" height="25" valign="center" align="left" style="padding-left:8px"> &nbsp;  
	   		  				<font size="1" color="#000000"><b>'.$szerznevtabl.'</b></font></td>';	     					
	   						}
	   						else
	   						{






	   			$naphatterszin="#D8D8D6";
	   		
	   			$knapwn=$cknap;
	   			if ($cknap<10) $knapwn='0'.$cknap;
	   			$sqlbasedtime=$dmev.'-'.$dmho.'-'.$knapwn;

	   			//print ("sqlbasedtime:$sqlbasedtime");

// unnepnap kieg.

	   			$szamlunnepnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='u'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlunnepnap++;
				}
				if ($szamlunnepnap!=0) $betuszin="#FF0000";

// unnepnap kieg. vege

// kotelezo szabi kieg.

	   			$szamlunnepnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='ksz'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlunnepnap++;
				}
				if ($szamlunnepnap!=0) $naphatterszin="#ff7a7a"; 

// kotelezo szabi kieg. vege

// kotelezo munkanap kieg.

	   			$szamlmnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='mn'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlmnap++;
				}
				if ($szamlmnap!=0) $betuszin="#00000";

// kotelezo munkanap kieg.

	   			if ($om[$knap]=="i") $naphatterszin="#C8DDC6"; 






	   						$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'">
	   						<font size="1" color="'.$betuszin.'">
	   							<b>'.$szerzodeskocka[$szerzszaml][$cknap].'</b></font></td>';
	   						$pszerzodeskocka=str_replace(".", ",", $szerzodeskocka[$szerzszaml][$cknap]);
	   						$kiirprint.='<td width="25" height="25" valign="center" align="center">
	   						<font size="1">
	   							<b>'.$pszerzodeskocka.'</b></font></td>';	   							
	   						$szerzoszlopossz[$cknap]=$szerzoszlopossz[$cknap]+$szerzodeskocka[$szerzszaml][$cknap];
	   						$sorvegiossz=$sorvegiossz+$szerzodeskocka[$szerzszaml][$cknap];
	   						}
	   					}
	   				$sorvegiossz_nap=round($sorvegiossz/8);
					$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1"><b>
								<i>'.$sorvegiossz.'</b><br>('.$sorvegiossz_nap.')</i></font></td></tr>'; // cégen belüli szerzősédek osszes munkaorajat es munkanapjat adja meg --------
					$psorvegiossz=str_replace(".", ",", $sorvegiossz);
					$psorvegiossz_nap=str_replace(".", ",", $sorvegiossz_nap);
					$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1"><b>
								<i>'.$psorvegiossz.'</b><br>('.$sorvegiossz_nap.')</i></font></td></tr>';								

	   	// job részletezés
	   						if ($szid==$szerzidtabl)
								{
	   							for ($jobszaml = 1; $jobszaml <= $jobsorok; $jobszaml++)
	   								{
	   								$jobidtabl=$jobkocka[$jobszaml][0];
	   								$sorvegiossz=0;
	   								$kiirkep.='<tr>';
	   								$kiirprint.='<tr>';	   								
	   								for ($cknap = 0; $cknap <= $datet; $cknap++)
	   									{
	   									$changedb="";
	   									$projectdb="";

	   									$lekerdezchn = mysql_query ("SELECT * FROM jobs WHERE id='$jobidtabl'");
	   									while ($sorchn = mysql_fetch_array ($lekerdezchn)) 
	   										{ 
	   										$changedb=$sorchn[changepipa]; 
	   										$projectdb=$sorchn[projectpipa];
	   										}

	   									if ($changedb=="y") { $chossz[$cknap]=$chossz[$cknap]+$jobkocka[$jobszaml][$cknap]; } 
											//else { $chnelkossz[$cknap]=$chnelkossz[$cknap]+$jobkocka[$jobszaml][$cknap]; }



	   									
	   		//							$lekerdezprn = mysql_query ("SELECT * FROM jobs WHERE id='$jobidtabl'");
	   		//							while ($sorprn = mysql_fetch_array ($lekerdezprn)) { $projectdb=$sorprn[projectpipa]; }

	   									if ($projectdb=="y") { $prossz[$cknap]=$prossz[$cknap]+$jobkocka[$jobszaml][$cknap]; } 
	   										//else { $prnelkossz[$cknap]=$prnelkossz[$cknap]+$jobkocka[$jobszaml][$cknap]; }

	   									if ($projectdb!="y" AND $changedb!="y") {
	   										$chnelkossz[$cknap]=$chnelkossz[$cknap]+$jobkocka[$jobszaml][$cknap];
	   									}

	   									$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
	   									if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }	   			
	   									if ($cknap=="0")
	   										{
	   										$jobidtabl=$jobkocka[$jobszaml][0];
	   										$lekerdezjobnev = mysql_query ("SELECT *
	   																		FROM jobs 
	   																		WHERE id='$jobidtabl'");
	   										while ($sorjob = mysql_fetch_array ($lekerdezjobnev)) 
												{
	   											$jobnevtabl=substr($sorjob[jobname], 0, 50);
	   											$changerdb=$sorjob[changepipa];
	   											$projectrdb=$sorjob[projectpipa];
	   											$jjstatusz=$sorjob[jobstatus];
	   											$lekerdezjs=mysql_query ("SELECT * FROM lang WHERE id='$jjstatusz'");
	   											while ($sorjs = mysql_fetch_array ($lekerdezjs)) 
	   												{
													$jstatusz=$sorjs[progvalue];
	   												}
			  									$changec="";
			  									if ($changerdb=="y") $changec='<font size="1" color="#FF0000">C ';
			  									if ($changerdb=="y") $changecp='<font size="1">C ';
	   											$projectc="";
			  									if ($projectrdb=="y") $projectc='<font size="1" color="#00AA00">P ';
			  									if ($projectrdb=="y") $projectcp='<font size="1">P ';
	   											}

	   										// feladat főfeladat-e lekérdezés

	   										$r5query4="SELECT * FROM jobs WHERE szulofeladatid='$jobidtabl'";
	   										$r5result4=mysql_query($r5query4);
	   										$r5numrows=mysql_num_rows($r5result4);
	   										if ($r5numrows!=0) {
	   											$F_azon=' <font color="#FF0000">F</font> '; // fofeladat elotti F ---------------------------------------------------
	   										} else {
	   											$F_azon="";
	   										}


	   											

	   										$kiirkep.='
	   											<td width="400" height="25" valign="center" align="left" bgcolor="#F8F8F6">
	   											<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-width: 0">
	   												<tr>
	   		  									<td width="375" height="25" valign="center" align="left" bgcolor="#F8F8F6" style="padding-left:8px"> &nbsp; &nbsp; &nbsp; '.$F_azon.' (<a href="?lang='.$lang.'&a=jobdetails&jid='.$jobidtabl.'&rfr=y" target="'.$jobidtabl.'"><font color="#000000">'.$jobidtabl.'</a>) 
	   		  									<a href="?lang='.$lang.'&a=riport99&dmev='.$dmev.'&dmho='.$dmho.'&cid='.$cid.'&szid='.$szerzidtabl.'&aid='.$alkidtabl.'&jidg='.$jobidtabl.'">
	   		  									'.$changec.$projectc.'<font size="1" color="#000000"><b>'.$jobnevtabl.'</a>
	   		  									</b></font></td>
	   		  									<td width="25" height="25" valign="center" align="center" bgcolor="#F8F8F6" style="padding-left:8px"><font size="1">
	   		  									<i>'.$jstatusz.'</i></td>
	   		  									</tr>
	   		  									</table>
	   		  									</td>'; //jobokat es az azok utan levo statuszt listazza -------------------
	   										$kiirprint.='
	   											<td width="400" height="25" valign="center" align="left">
	   											<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-width: 0">
	   												<tr>
	   		  									<td width="375" height="25" valign="center" align="left" style="padding-left:8px"> &nbsp; &nbsp; &nbsp;  
	   		  									'.$changecp.$projectcp.'<font size="1">('.$jobidtabl.') <b>'.$jobnevtabl.'
	   		  									</b></font></td>
	   		  									<td width="25" height="25" valign="center" align="center" style="padding-left:8px"><font size="1">
	   		  									<i>'.$jstatusz.'</i></td>
	   		  									</tr>
	   		  									</table>
	   		  									</td>';	   		  									

	   										}
	   										else
	   										{

// 28/30/31 nap felsorolása
								   			$naphatterszin="#F8F8F6";
								   		
								   			$knapwn=$cknap;
								   			if ($cknap<10) $knapwn='0'.$cknap;
								   			$sqlbasedtime=$dmev.'-'.$dmho.'-'.$knapwn;

								   			//print ("sqlbasedtime:$sqlbasedtime");

							// unnepnap kieg.

								   			$szamlunnepnap=0;
								   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='u'") or die (mysql_error());
											while ($sor = mysql_fetch_array ($lekerdez)) { 
												$szamlunnepnap++;
											}
											if ($szamlunnepnap!=0) $betuszin="#FF0000";

							// unnepnap kieg. vege

							// kotelezo szabi kieg.

								   			$szamlunnepnap=0;
								   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='ksz'") or die (mysql_error());
											while ($sor = mysql_fetch_array ($lekerdez)) { 
												$szamlunnepnap++;
											}
											if ($szamlunnepnap!=0) $naphatterszin="#ff7a7a"; 

							// kotelezo szabi kieg. vege

							// kotelezo munkanap kieg.

								   			$szamlmnap=0;
								   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='mn'") or die (mysql_error());
											while ($sor = mysql_fetch_array ($lekerdez)) { 
												$szamlmnap++;
											}
											if ($szamlmnap!=0) $betuszin="#00000";

							// kotelezo munkanap kieg.

								   			if ($om[$knap]=="i") $naphatterszin="#C8DDC6"; 



	   										$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1" color="'.$betuszin.'">
	   											<b>'.$jobkocka[$jobszaml][$cknap].'</b></font></td>'; // munkaorkat listazza -------
	   										$pjobkocka=str_replace(".", ",", $jobkocka[$jobszaml][$cknap]);
	   										$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
	   											<b>'.$pjobkocka.'</b></font></td>';	   											
	   										$joboszlopossz[$cknap]=$joboszlopossz[$cknap]+$jobkocka[$jobszaml][$cknap];
	   										$sorvegiossz=$sorvegiossz+$jobkocka[$jobszaml][$cknap];
	   										}
	   									}
	   									$sorsumall=0;
	   									$lekerdezjobsum = mysql_query ("SELECT SUM(hours) AS sumhours FROM workhours WHERE ((whtype IS NULL) AND (jobid='$jobidtabl'))");
	   									while ($sorsum = mysql_fetch_array ($lekerdezjobsum)) 
											{
	   										$sorsumall=$sorsum[sumhours];
	   										}

// SELECT SUM(`hours`) AS sumhours FROM `workhours` WHERE `whtype` IS NULL AND `jobid`='21000'


// alfeladatok óráinak hozzáadása a szummához

	   								$r5query3="	SELECT SUM(workhours.hours) AS osszesora 
												FROM workhours 
												INNER JOIN jobs ON jobs.id=workhours.jobid
	   											WHERE szulofeladatid='$jobidtabl'
	   											";
	   								//echo '<br>r5query3'.$r5query3;
	   								$r5result3=mysql_query($r5query3);
	   								$r5row3=mysql_fetch_assoc($r5result3);
	   								//echo ' ora:'.$r5row3['osszesora'];

	   								if ($r5row3['osszesora']!="") {
	   									$sorsumall=$sorsumall+$r5row3['osszesora'];
	   								}

// alfeladatok óráinak hozzáadása a szummához vége





	   								if ($changec=="") 
	   									{
	   									$sorsumallcn=$sorsumallcn+$sorsumall;
	   									}
	   									else
	   									{
	   									$sorsumallcy=$sorsumallcy+$sorsumall;	
	   									}




	   								$sorsumall=str_replace(".0", "", $sorsumall);

	   								$sorvegiossz_nap=round($sorvegiossz/8);
	   								$sorsumall_nap=round($sorsumall/8);

									$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1"><b>
												<i>'.$sorvegiossz.' </b><br>('.$sorvegiossz_nap.')</i></font></td>
												<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1"><b>
												<i> &nbsp;'.$sorsumall.'&nbsp; </b><br>('.$sorsumall_nap.')</i></font></td></tr>'; // jobonkent a havi es osszes munkaorak osszesitese 
									$psorvegiossz=str_replace(".", ",", $sorvegiossz);
									$psorvegiossz_nap=str_replace(".", ",", $sorvegiossz_nap);
									$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1"><b>
												<i>'.$psorvegiossz.' ('.$sorsumall.')</b><br>('.$psorvegiossz_nap.' ('.$sorsumall_nap.'))</i></font></td></tr>';												

	   	// user részletezés
	   								if ($jidg==$jobidtabl)
										{
	   									for ($usrszaml = 1; $usrszaml <= $usrsorok; $usrszaml++)
	   										{
	   										$usridtabl=$usrkocka[$usrszaml][0];
	   										$sorvegiossz=0;
	   										$kiirkep.='<tr>';
	   										$kiirprint.='<tr>';
	   										for ($cknap = 0; $cknap <= $datet; $cknap++)
	   											{
	   											$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
	   											if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }	   			
	   											if ($cknap=="0")
	   												{
	   												$usridtabl=$usrkocka[$usrszaml][0];
	   												$lekerdezusrnev = mysql_query ("SELECT * FROM users WHERE id='$usridtabl'");
	   												while ($sorusr = mysql_fetch_array ($lekerdezusrnev)) 
														{
	   													$usrnevtabl=$sorusr[name];
	   													}
	   												$kiirkep.='
	   		  											<td width="400" height="25" valign="center" align="left" bgcolor="#FFFFFF" style="padding-left:8px"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  
			   		  									<font size="1" face="Verdana" color="#000000"><b>'.$usrnevtabl.'
	    		 										</b></font></td>'; //feladathoz tartozo nevek kiiratasa 
	   												$kiirprint.='
	   		  											<td width="400" height="25" valign="center" align="left" style="padding-left:8px"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  
			   		  									<font size="1" face="Verdana"><b>'.$usrnevtabl.'
	    		 										</b></font></td>';	    		 										
	   												}
	   												else
	   												{




										   			$naphatterszin="#FFFFFF";
										   		
										   			$knapwn=$cknap;
										   			if ($cknap<10) $knapwn='0'.$cknap;
										   			$sqlbasedtime=$dmev.'-'.$dmho.'-'.$knapwn;

										   			//print ("sqlbasedtime:$sqlbasedtime");

													// unnepnap kieg.

										   			$szamlunnepnap=0;
										   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='u'") or die (mysql_error());
													while ($sor = mysql_fetch_array ($lekerdez)) { 
														$szamlunnepnap++;
													}
													if ($szamlunnepnap!=0) $betuszin="#FF0000";

													// unnepnap kieg. vege

													// kotelezo szabi kieg.

										   			$szamlunnepnap=0;
										   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='ksz'") or die (mysql_error());
													while ($sor = mysql_fetch_array ($lekerdez)) { 
														$szamlunnepnap++;
													}
													if ($szamlunnepnap!=0) $naphatterszin="#ff7a7a"; 

													// kotelezo szabi kieg. vege

													// kotelezo munkanap kieg.

										   			$szamlmnap=0;
										   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='mn'") or die (mysql_error());
													while ($sor = mysql_fetch_array ($lekerdez)) { 
														$szamlmnap++;
													}
													if ($szamlmnap!=0) $betuszin="#00000";

													// kotelezo munkanap kieg.

										   			if ($om[$knap]=="i") $naphatterszin="#C8DDC6"; 






	   												$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1" color="'.$betuszin.'">
	   													<b>'.$usrkocka[$usrszaml][$cknap].'</b></font></td>'; //feladaton belül a dolgozók oráit listázza 
	   												$pusrkocka=str_replace(".", ",", $usrkocka[$usrszaml][$cknap]);
	   												$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
	   													<b>'.$pusrkocka.'</b></font></td>';	   													
	   												$usroszlopossz[$cknap]=$usroszlopossz[$cknap]+$usrkocka[$usrszaml][$cknap];
			   										$sorvegiossz=$sorvegiossz+$usrkocka[$usrszaml][$cknap];
	   												}
	   											}
	   										$sorvegiossz_nap=round($sorvegiossz/8);
											$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1">
														<b><i>'.$sorvegiossz.'</b><br>('.$sorvegiossz_nap.')</i></font></td></tr>'; // a fenti összesitese es napra bontasa 
											$psorvegiossz=str_replace(".", ",", $sorvegiossz);
											$psorvegiossz_nap=str_replace(".", ",", $sorvegiossz_nap);
											$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
														<b><i>'.$psorvegiossz.'</b><br>('.$psorvegiossz_nap.')</i></font></td></tr>';														

/*
 *	Alfeladat userek listázása
 */

	   										}

	   										

											$query_AL = "	SELECT userid, name, SUM(hours) AS alfelhours, RIGHT(whdate,2) AS shortwhdate
															FROM workhours
															LEFT OUTER JOIN jobs ON jobs.id = workhours.jobid
															LEFT OUTER JOIN users ON users.id = workhours.userid
															WHERE szulofeladatid = '$jidg' AND whdate LIKE '$dmev$dmho%' AND whtype IS NULL
															GROUP BY userid, RIGHT(whdate,2)";
											
//											echo '<br>q_AL:'.$query_AL;
											

											//$kiirkep.='<tr><td colspan="20">'.$query_AL.'</td></tr>';
											$result_AL = mysql_query($query_AL);
											while ($row_AL=mysql_fetch_assoc($result_AL)) {
												$al_fel_userid=$row_AL['userid'];
												$al_fel_name=$row_AL['name'];
												$al_fel_hours=$row_AL['alfelhours'];
												$al_fel_whdate=round($row_AL['shortwhdate']);
												$alfeladatMatrix[$al_fel_userid][$al_fel_whdate]=$al_fel_hours;
											}

											//var_dump($alfeladatMatrix);


											$query_AL = "	SELECT userid, name
															FROM workhours
															LEFT OUTER JOIN jobs ON jobs.id = workhours.jobid
															LEFT OUTER JOIN users ON users.id = workhours.userid
															WHERE szulofeladatid = '$jidg' AND whdate LIKE '$dmev$dmho%' AND whtype IS NULL
															GROUP BY userid
															ORDER BY name";
											
											$result_AL = mysql_query($query_AL);
											while ($row_AL=mysql_fetch_assoc($result_AL)) {
												$al_fel_userid=$row_AL['userid'];
												$al_fel_name=$row_AL['name'];
												//$al_fel_hours=$row['alfelhours'];
												//$al_fel_whdate=$row['whdate'];
												//$alfeladatMatrix[$al_fel_userid][$al_fel_whdate]=$al_fel_hours;

													$feladatIDk="";
													$query_alfeladat_jobid_kereso = "	SELECT jobid, jobname 
																						FROM workhours
																						LEFT OUTER JOIN jobs ON jobs.id = workhours.jobid
																						WHERE szulofeladatid = '$jidg' AND whdate LIKE '$dmev$dmho%' AND userid = '$al_fel_userid'
																						GROUP BY workhours.jobid
																						ORDER BY workhours.jobid";
													$result_alfeladat_jobid_kereso = mysql_query($query_alfeladat_jobid_kereso);
													while ($row_alfeladat_jobid_kereso = mysql_fetch_assoc($result_alfeladat_jobid_kereso)) {
														$feladatIDk.='<a target="jobdetails" href="?lang='.$lang.'&a=jobdetails&jid='.$row_alfeladat_jobid_kereso['jobid'].'"><font color="#000000">'.$row_alfeladat_jobid_kereso['jobid'].'</a>, ';
														$feladatIDJustNum[] = $row_alfeladat_jobid_kereso['jobid'];
													//	echo'asd:';
														//var_dump($feladatIDJustNum);
													}
													
													$query_alfeladat_jobid_kereso_withoutwork = "	SELECT jobid, jobname 
																																				FROM workhours
																																				LEFT OUTER JOIN jobs ON jobs.id = workhours.jobid
																																				WHERE szulofeladatid = '$jidg' AND whdate NOT LIKE '$dmev$dmho%'
																																				GROUP BY workhours.jobid
																																				ORDER BY workhours.jobid";
														
														$result_alfeladat_jobid_kereso_withoutwork = mysql_query($query_alfeladat_jobid_kereso_withoutwork);
														while ($row_alfeladat_jobid_kereso_withoutwork = mysql_fetch_assoc($result_alfeladat_jobid_kereso_withoutwork)) {
														
														$feladatIDJustNum[] = $row_alfeladat_jobid_kereso_withoutwork['jobid'];
													//	echo'asd:';
														//var_dump($feladatIDJustNum);
													}

												//$feladatIDJustNumSort=sort($feladatIDJustNum);
												$feladatIDJustNumUnique=array_unique($feladatIDJustNum);
												//echo'<br>qwe:';
													//	var_dump($feladatIDJustNumSortUni);

												$kiirkep.='<tr><td bgcolor="#FFFFFF"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b><i><font color="#551A8B" size="1">A</font></i></b><font size="1"> '.$al_fel_name.' ('.$feladatIDk.')</font></td>'; 
												//Az alfeladat User elotti A betu es a userek az alfeladatikkal -----------------------------------------------------------------------------------------------------------------------------------
												$kiirprint.='<tr><td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <font size="1"><b><i>A</i></b> '.$al_fel_name.'</font></td>';
												
												

												$sorvegiossz=0;
												for ($cknap = 1; $cknap <= $datet; $cknap++) {
		   											$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
		   											if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }	   	
		   											$alfeladatMatrix[$al_fel_userid][$cknap]=str_replace(".0", "", $alfeladatMatrix[$al_fel_userid][$cknap]);
		   											$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1" color="'.$betuszin.'">
	   													<b>'.$alfeladatMatrix[$al_fel_userid][$cknap].'</b></font></td>';
	   												$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
	   													<b>'.$alfeladatMatrix[$al_fel_userid][$cknap].'</b></font></td>';
	   													$sorvegiossz=$sorvegiossz+$alfeladatMatrix[$al_fel_userid][$cknap];
		   										}
											$sorvegiossz_nap=round($sorvegiossz/8);
											$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1">
														<b><i>'.$sorvegiossz.'</b><br>('.$sorvegiossz_nap.')</i></font></td></tr>';
											$psorvegiossz=str_replace(".", ",", $sorvegiossz);
											$psorvegiossz_nap=str_replace(".", ",", $sorvegiossz_nap);
											$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
														<b><i>'.$psorvegiossz.'</b><br>('.$psorvegiossz_nap.')</i></font></td></tr>';

												$kiirkep.='</tr>';


											}

											

											// if($fejlesztes == 1) {

											$alfeladatok = explode(",", $feladatIDk);
											$alfeladatTombHossza = sizeof($alfeladatok);

											$i = 0;

											foreach ($feladatIDJustNumUnique as $key => $value) {
	
											//for($i = 0; $i < $alfeladatTombHossza - 1; $i++ ){

												$alfeladatid = $alfeladatokNeve[$i];
												//settype($alfeladatid, "integer");
												$queryAlfeladatok = "SELECT * FROM jobs WHERE id = '$value'";
												$alfeladatSql = mysql_query($queryAlfeladatok);
												$alfeladatRow = mysql_fetch_assoc($alfeladatSql);

													$kiirkep.= '<tr><td bgcolor="#FFFFFF" width="25" height="25" valign="center"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; <font size="1">(<a target="jobdetails" href="?lang='.$lang.'&a=jobdetails&jid='.$value.'"><font color="red">'.$value.'</font></a>)  <a href="?lang='.$lang.'&a=riport99&dmev='.$dmev.'&dmho='.$dmho.'&cid='.$cid.'&szid='.$szerzidtabl.'&aid='.$alkidtabl.'&jidg='.$jobidtabl.'&jobWorkers=1&jid='.$value.'"><font color="red" size="1">'.$alfeladatRow['jobname'].'</a></size></td>'; // alfeladat kiiro ---------------------------------------
													$kiirprint.='<tr><td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<font size="1">'.$value.''.$alfeladatRow['jobname'].'</font></td>';

													//---------------------------------------------
		
														$query_AL_2 = "	SELECT SUM(hours) AS ossz, RIGHT(whdate,2) AS nap 
																						FROM workhours WHERE jobid = '$value' AND whdate LIKE '$dmev$dmho%' AND whtype IS NULL
																						group by RIGHT(whdate, 2) ";

														
																
																//$al_fel_hours=$row['alfelhours'];
																//$al_fel_whdate=$row['whdate'];
																//$alfeladatMatrix[$al_fel_userid][$al_fel_whdate]=$al_fel_hours;
											
															$result_AL_2 = mysql_query($query_AL_2);
															while ($row_AL=mysql_fetch_assoc($result_AL_2)) {
																$nap = round($row_AL['nap']);
																$jobidja = $value;

																$matrix[$jobidja][$nap] = $row_AL['ossz'];
																
																//$alfelossz = $row_AL['ossz'];

																}

																	$sorvegiossz=0;
																	for ($cknap = 1; $cknap <= $datet; $cknap++) {
																			$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
																			if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }	   	
																			
																			$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="#FFFFFF"><font size="1" color="red">
																				<b>'.$matrix[$value][$cknap].'</b></font></td>';
																			$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
																				<b>'.$matrix[$value][$cknap].'</b></font></td>';
																				$sorvegiossz=$sorvegiossz+$matrix[$value][$cknap]; // alfeladat osszesen ---------------------------------
																			
																			}
																			$sorvegiossz_nap=round($sorvegiossz/8);
																			$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="#FFFFFF"><font size="1" color="red">
																						<b><i>'.$sorvegiossz.'</b><br>('.$sorvegiossz_nap.')</i></font></td>';
																			$psorvegiossz=str_replace(".", ",", $sorvegiossz);
																			$psorvegiossz_nap=str_replace(".", ",", $sorvegiossz_nap);
																			$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1"><b>
																						<i>'.$psorvegiossz.'('.$sorsumall.')</b><br>('.$psorvegiossz_nap.' ('.$sorsumall_nap.'))</i></font></td></tr>';
																			// $kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
																			// 			<b><i>'.$psorvegiossz.'</b><br>('.$psorvegiossz_nap.')</i></font></td>';


															
															
														
													//---------------------------------------------

														$r5query666="	SELECT SUM(workhours.hours) AS osszesora 
																			FROM workhours 
																			INNER JOIN jobs ON jobs.id=workhours.jobid
																			WHERE jobid = '$value'
																			";
																			//echo '<br>r5query3'.$r5query3;
																			$r5result667=mysql_query($r5query666);
																			$r5row668=mysql_fetch_assoc($r5result667);
																			//echo ' ora:'.$r5row3['osszesora'];

																			if ($r5row668['osszesora']!="") {
																					$sorsumall=$r5row668['osszesora'];
																			}

																			// alfeladatok óráinak hozzáadása a szummához vége

																			if ($changec=="") 
																			{
																				$sorsumallcn=$sorsumallcn+$sorsumall;
																			}
																			else
																			{
																				$sorsumallcy=$sorsumallcy+$sorsumall;	
																			}

																			$sorsumall=str_replace(".0", "", $sorsumall);

																			$sorvegiossz_nap=round($sorvegiossz/8);
																			$sorsumall_nap=round($sorsumall/8);

																			$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="#FFFFFF"><font size="1" color="red"><b>
																								 <i> &nbsp;'.$sorsumall.'&nbsp; </b><br>('.$sorsumall_nap.')</i></font></td></tr>'; // jobonkent a havi es osszes munkaorak osszesitese 
																			$psorvegiossz=str_replace(".", ",", $sorvegiossz);
																			$psorvegiossz_nap=str_replace(".", ",", $sorvegiossz_nap);
																			// $kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="4"><b>
																			// 			<i>'.$psorvegiossz.' ('.$sorsumall.')</b><br>('.$psorvegiossz_nap.' ('.$sorsumall_nap.'))</i></font></td></tr>';
																									



													if(isset($jobWorkers) && $jobWorkers == 1 && $jid == $value) {

															$query_AL = "	SELECT userid, name, hours AS alfelhours, RIGHT(whdate,2) AS shortwhdate
															FROM workhours
															LEFT OUTER JOIN jobs ON jobs.id = workhours.jobid
															LEFT OUTER JOIN users ON users.id = workhours.userid
															WHERE jobid = '$value' AND whdate LIKE '$dmev$dmho%' AND whtype IS NULL
															GROUP BY userid, RIGHT(whdate,2)";
											
															//echo '<br>q_AL:'.$query_AL;
											

															//$kiirkep.='<tr><td colspan="20">'.$query_AL.'</td></tr>';
															$result_AL = mysql_query($query_AL);
															while ($row_AL=mysql_fetch_assoc($result_AL)) {
															$al_fel_userid=$row_AL['userid'];
															$al_fel_name=$row_AL['name'];
															$al_fel_hours=$row_AL['alfelhours'];
															$al_fel_whdate=round($row_AL['shortwhdate']);
															$alfeladatMatrixEgyAlfeladat[$al_fel_userid][$al_fel_whdate]=$al_fel_hours;
															}

															//var_dump($alfeladatMatrix);


															$query_AL = "	SELECT userid, name
															FROM workhours
															LEFT OUTER JOIN jobs ON jobs.id = workhours.jobid
															LEFT OUTER JOIN users ON users.id = workhours.userid
															WHERE jobid = '$value' AND whdate LIKE '$dmev$dmho%' AND whtype IS NULL
															GROUP BY userid
															ORDER BY name";
											
															$result_AL = mysql_query($query_AL);
															while ($row_AL=mysql_fetch_assoc($result_AL)) {
																$al_fel_userid=$row_AL['userid'];
																$al_fel_name=$row_AL['name'];
																//$al_fel_hours=$row['alfelhours'];
																//$al_fel_whdate=$row['whdate'];
																//$alfeladatMatrix[$al_fel_userid][$al_fel_whdate]=$al_fel_hours;
											
															$result_AL = mysql_query($query_AL);
															while ($row_AL=mysql_fetch_assoc($result_AL)) {
																$al_fel_userid=$row_AL['userid'];
																$al_fel_name=$row_AL['name'];
																$kiirkep.='<tr><td bgcolor="#FFFFFF"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <font size="1" color="red"> '.$al_fel_name.'</size></td>';
																$kiirprint.='<tr><td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<font size="1"> '.$al_fel_name.'</size></td>';

																	$sorvegiossz=0;
																	for ($cknap = 1; $cknap <= $datet; $cknap++) {
																			$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
																			if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }	   	
																			$alfeladatMatrixEgyAlfeladat[$al_fel_userid][$cknap]=str_replace(".0", "", $alfeladatMatrixEgyAlfeladat[$al_fel_userid][$cknap]);
																			$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="#FFFFFF"><font size="1" color="red">
																				<b>'.$alfeladatMatrixEgyAlfeladat[$al_fel_userid][$cknap].'</b></font></td>';
																			$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
																				<b>'.$alfeladatMatrixEgyAlfeladat[$al_fel_userid][$cknap].'</b></font></td>';
																				$sorvegiossz=$sorvegiossz+$alfeladatMatrixEgyAlfeladat[$al_fel_userid][$cknap]; // alfeladat osszesen ---------------------------------
																			}
																			$sorvegiossz_nap=round($sorvegiossz/8);
																			$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="#FFFFFF"><font size="1" color="red">
																						<b><i>'.$sorvegiossz.'</b><br>('.$sorvegiossz_nap.')</i></font></td>';
																			$psorvegiossz=str_replace(".", ",", $sorvegiossz);
																			$psorvegiossz_nap=str_replace(".", ",", $sorvegiossz_nap);
																			$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
																						<b><i>'.$psorvegiossz.'</b><br>('.$psorvegiossz_nap.')</i></font></td>';

																			

																			//SUM

																			$r5query3="	SELECT SUM(workhours.hours) AS osszesora 
																			FROM workhours 
																			INNER JOIN jobs ON jobs.id=workhours.jobid
																			WHERE jobid = '$value' AND userid = '$al_fel_userid'
																			";
																			//echo '<br>r5query3'.$r5query3;
																			$r5result3=mysql_query($r5query3);
																			$r5row3=mysql_fetch_assoc($r5result3);
																			//echo ' ora:'.$r5row3['osszesora'];

																			if ($r5row3['osszesora']!="") {
																					$sorsumall=$r5row3['osszesora'];
																			}

																			// alfeladatok óráinak hozzáadása a szummához vége

																			if ($changec=="") 
																			{
																				$sorsumallcn=$sorsumallcn+$sorsumall;
																			}
																			else
																			{
																				$sorsumallcy=$sorsumallcy+$sorsumall;	
																			}

																			$sorsumall=str_replace(".0", "", $sorsumall);

																			$sorvegiossz_nap=round($sorvegiossz/8);
																			$sorsumall_nap=round($sorsumall/8);

																			// $kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="#FFFFFF"><font size="4" color="red"><b>
																			// 					 <i> &nbsp;'.$sorsumall.'&nbsp; </b><br>('.$sorsumall_nap.')</i></font></td></tr>'; // jobonkent a havi es osszes munkaorak osszesitese 
																			$psorvegiossz=str_replace(".", ",", $sorvegiossz);
																			$psorvegiossz_nap=str_replace(".", ",", $sorvegiossz_nap);
																			// $kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1"><b>
																			// 			<i>'.$psorvegiossz.' ('.$sorsumall.')</b><br>('.$psorvegiossz_nap.' ('.$sorsumall_nap.'))</i></font></td></tr>';
																									}

																			$kiirkep.='</tr>';
																			//$kiirkep.='<tr><td bgcolor="#FFFFFF"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <font size="1"> '.$al_fel_name.'</size></td>';

													}

													$i++;
											}
											}

	   									}
											//  }
											 
	   	// user részletezés vége
	   								}
	   							}
	   							//if ($szid!="")
	   							if ($szid==$szerzidtabl)
	   								{
	   			   					$kiirkep.='<tr>';
	   			   					$kiirprint.='<tr>';
	   								for ($cknap = 0; $cknap <= $datet; $cknap++)
	   									{

	   									$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
	   									if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }	   			
	   									if ($cknap=="0")
	   										{
	   										$kiirkep.='
	   		  									<td width="400" height="25" valign="center" align="left" bgcolor="#DDEEEE" style="padding-left:8px"> &nbsp; &nbsp; &nbsp;  
	   		  									<b>changeosszesen</b></font></td>';
	   										$kiirprint.='
	   		  									<td width="400" height="25" valign="center" align="left" style="padding-left:8px"> &nbsp; &nbsp; &nbsp;  
	   		  									<b><font size="1">changeosszesen</b></font></td>';	   		  									
	   										}
	   										else
	   										{





								   			$naphatterszin="#DDEEEE";
								   		
								   			$knapwn=$cknap;
								   			if ($cknap<10) $knapwn='0'.$cknap;
								   			$sqlbasedtime=$dmev.'-'.$dmho.'-'.$knapwn;

								   			//print ("sqlbasedtime:$sqlbasedtime");

											// unnepnap kieg.

								   			$szamlunnepnap=0;
								   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='u'") or die (mysql_error());
											while ($sor = mysql_fetch_array ($lekerdez)) { 
												$szamlunnepnap++;
											}
											if ($szamlunnepnap!=0) $betuszin="#FF0000";

											// unnepnap kieg. vege

											// kotelezo szabi kieg.

								   			$szamlunnepnap=0;
								   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='ksz'") or die (mysql_error());
											while ($sor = mysql_fetch_array ($lekerdez)) { 
												$szamlunnepnap++;
											}
											if ($szamlunnepnap!=0) $naphatterszin="#ff7a7a"; 

											// kotelezo szabi kieg. vege

											// kotelezo munkanap kieg.

								   			$szamlmnap=0;
								   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='mn'") or die (mysql_error());
											while ($sor = mysql_fetch_array ($lekerdez)) { 
												$szamlmnap++;
											}
											if ($szamlmnap!=0) $betuszin="#00000";

											// kotelezo munkanap kieg.

								   			if ($om[$knap]=="i") $naphatterszin="#C8DDC6"; 




	   										if ($chossz[$cknap]=="0") { $chosszkiir=""; } else { $chosszkiir=$chossz[$cknap]; }
	   										$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1" color="'.$betuszin.'">
	   											<b>'.$chosszkiir.'</b></font></td>';
	   										$pchosszkiir=str_replace(".", ",", $chosszkiir);
	   										$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
	   											<b>'.$pchosszkiir.'</b></font></td>';	   											
	   										$chsorvegiossz=$chsorvegiossz+$chossz[$cknap];
	   										}
	   									}
	   								$chsorvegiossz_nap=round($chsorvegiossz/8);
	   								$sorsumallcy_nap=round($sorsumallcy/8);
									$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1"><b>
												<i>'.$chsorvegiossz.'</b><br>('.$chsorvegiossz_nap.')</i></font></td>
												<td width="25" height="25" valign="center" align="center" bgcolor="#DDFFFF">
											<font size="1"><b><i>'.$sorsumallcy.'</b><br>('.$sorsumallcy_nap.')</i></font></td></tr>';
									$pchsorvegiossz=str_replace(".", ",", $chsorvegiossz);
									$pchsorvegiossz_nap=str_replace(".", ",", $chsorvegiossz_nap);
									$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1"><b>
												<i>'.$pchsorvegiossz.'</b><br>('.$pchsorvegiossz_nap.')</i></font></td></tr>';










	   			   					$kiirkep.='<tr>';
	   			   					$kiirprint.='<tr>';
	   								for ($cknap = 0; $cknap <= $datet; $cknap++)
	   									{

	   									$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
	   									if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }	   			
	   									if ($cknap=="0")
	   										{
	   										$kiirkep.='
	   		  									<td width="400" height="25" valign="center" align="left" bgcolor="#DDF6F6" style="padding-left:8px"> &nbsp; &nbsp; &nbsp;  
	   		  									<b>projectosszesen</b></font></td>';
	   										$kiirprint.='
	   		  									<td width="400" height="25" valign="center" align="left" style="padding-left:8px"> &nbsp; &nbsp; &nbsp;  
	   		  									<b><font size="1">projectosszesen</b></font></td>';	   		  									
	   										}
	   										else
	   										{





								   			$naphatterszin="#DDF6F6";
								   		
								   			$knapwn=$cknap;
								   			if ($cknap<10) $knapwn='0'.$cknap;
								   			$sqlbasedtime=$dmev.'-'.$dmho.'-'.$knapwn;

								   			//print ("sqlbasedtime:$sqlbasedtime");

											// unnepnap kieg.

								   			$szamlunnepnap=0;
								   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='u'") or die (mysql_error());
											while ($sor = mysql_fetch_array ($lekerdez)) { 
												$szamlunnepnap++;
											}
											if ($szamlunnepnap!=0) $betuszin="#FF0000";

											// unnepnap kieg. vege

											// kotelezo szabi kieg.

								   			$szamlunnepnap=0;
								   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='ksz'") or die (mysql_error());
											while ($sor = mysql_fetch_array ($lekerdez)) { 
												$szamlunnepnap++;
											}
											if ($szamlunnepnap!=0) $naphatterszin="#ff7a7a"; 

											// kotelezo szabi kieg. vege

											// kotelezo munkanap kieg.

								   			$szamlmnap=0;
								   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='mn'") or die (mysql_error());
											while ($sor = mysql_fetch_array ($lekerdez)) { 
												$szamlmnap++;
											}
											if ($szamlmnap!=0) $betuszin="#00000";

											// kotelezo munkanap kieg.

								   			if ($om[$knap]=="i") $naphatterszin="#C8DDC6"; 




	   										if ($prossz[$cknap]=="0") { $prosszkiir=""; } else { $prosszkiir=$prossz[$cknap]; }
	   										$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1" color="'.$betuszin.'">
	   											<b>'.$prosszkiir.'</b></font></td>';
	   										$pprosszkiir=str_replace(".", ",", $prosszkiir);
	   										$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
	   											<b>'.$pprosszkiir.'</b></font></td>';	   											
	   										$prsorvegiossz=$prsorvegiossz+$prossz[$cknap];
	   										}
	   									}
	   								$prsorvegiossz_nap=round($prsorvegiossz/8);
	   								$sorsumallcy_nap=round($sorsumallcy/8);
									$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1"><b>
												<i>'.$prsorvegiossz.'</b><br>('.$prsorvegiossz_nap.')</i></font></td>
												<td width="25" height="25" valign="center" align="center" bgcolor="#DDF6F6">
											<font size="1"><b><i>'.$sorsumallcy.'</b><br>('.$sorsumallcy_nap.')</i></font></td></tr>';
									$pprsorvegiossz=str_replace(".", ",", $prsorvegiossz);
									$pprsorvegiossz_nap=str_replace(".", ",", $prsorvegiossz_nap);
									$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1"><b>
												<i>'.$pprsorvegiossz.'</b><br>('.$pprsorvegiossz_nap.')</i></font></td></tr>';










	   								$kiirkep.='<tr>';
	   								$kiirprint.='<tr>';
	   								for ($cknap = 0; $cknap <= $datet; $cknap++)
	   									{

	   									$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
	   									if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }	   			
	   									if ($cknap=="0")
	   										{
	   										$kiirkep.='
	   		  									<td width="400" height="25" valign="center" align="left" bgcolor="#DDFFFF" style="padding-left:8px"> &nbsp; &nbsp; &nbsp;  
	   		  									<b>nemchangeosszesen</b></font></td>';
	   										$kiirprint.='
	   		  									<td width="400" height="25" valign="center" align="left" style="padding-left:8px"> &nbsp; &nbsp; &nbsp;  
	   		  									<b><font size="1">nemchangeosszesen</b></font></td>';	   		  									
	   										}
	   										else
	   										{




	   			$naphatterszin="#DDFFFF";
	   		
	   			$knapwn=$cknap;
	   			if ($cknap<10) $knapwn='0'.$cknap;
	   			$sqlbasedtime=$dmev.'-'.$dmho.'-'.$knapwn;

	   			//print ("sqlbasedtime:$sqlbasedtime");

// unnepnap kieg.

	   			$szamlunnepnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='u'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlunnepnap++;
				}
				if ($szamlunnepnap!=0) $betuszin="#FF0000";

// unnepnap kieg. vege

// kotelezo szabi kieg.

	   			$szamlunnepnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='ksz'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlunnepnap++;
				}
				if ($szamlunnepnap!=0) $naphatterszin="#ff7a7a"; 

// kotelezo szabi kieg. vege

// kotelezo munkanap kieg.

	   			$szamlmnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='mn'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlmnap++;
				}
				if ($szamlmnap!=0) $betuszin="#00000";

// kotelezo munkanap kieg.

	   			if ($om[$knap]=="i") $naphatterszin="#C8DDC6"; 




	   										if ($chnelkossz[$cknap]=="0") { $chnelkosszkiir=""; } else { $chnelkosszkiir=$chnelkossz[$cknap]; }
	   										$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1" color="'.$betuszin.'">
	   											<b>'.$chnelkosszkiir.'</b></font></td>';
	   										$pchnelkosszkiir=str_replace(".", ",", $chnelkosszkiir);
	   										$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
	   											<b>'.$pchnelkosszkiir.'</b></font></td>';	   											
	   										$chnsorvegiossz=$chnsorvegiossz+$chnelkossz[$cknap];
	   										}
	   									}
	   								$chnsorvegiossz_nap=round($chnsorvegiossz/8);
	   								$sorsumallcn_nap=round($sorsumallcn/8);
									$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'">
											<font size="1"><b><i>'.$chnsorvegiossz.'</b><br>('.$chnsorvegiossz_nap.')</i></font></td>
											<td width="25" height="25" valign="center" align="center" bgcolor="#DDFFFF">
											<font size="1"><b><i>'.$sorsumallcn.'</b><br>('.$sorsumallcn_nap.')</i></font></td></tr>';
									$pchnsorvegiossz=str_replace(".", ",", $chnsorvegiossz);
									$pchnsorvegiossz_nap=str_replace(".", ",", $chnsorvegiossz_nap);
									$kiirprint.='<td width="25" height="25" valign="center" align="center">
											<font size="1"><b><i>'.$pchnsorvegiossz.'</b><br>('.$pchnsorvegiossz_nap.')</i></font></td></tr>';											
									}
	   	// job részletezés vége
	   						}
	   					}
	   	// alkalmazás részletezés vége
//	   				}
//				}  		
   		// szerződéses részletezés vége
			}

	   		$kiirkep.='<tr><td width="400" height="25" valign="center" align="center" bgcolor="#D8D8D6"><font size="1"> 
	     			</b></font></td>'; //utolsó ures cella szine/tartalma! -------------------------------------------------------------------------------------------------------------------------------------------------------
	     	$kiirprint.='<tr><td width="400" height="25" valign="center" align="center"><font size="1"> 
	     			</b></font></td>';
	   		for ($cknap = 1; $cknap <= $datet; $cknap++)
	   			{
	   			$dnf=date("w", strtotime("$dmev-$dmho-$cknap"));
	   			if (($dnf=="6") OR ($dnf=="0")) { $betuszin="#FF0000"; } else { $betuszin="#000000"; }	  


	   			$naphatterszin="#D8D8D6";
	   		
	   			$knapwn=$cknap;
	   			if ($cknap<10) $knapwn='0'.$cknap;
	   			$sqlbasedtime=$dmev.'-'.$dmho.'-'.$knapwn;

	   			//print ("sqlbasedtime:$sqlbasedtime");

// unnepnap kieg.

	   			$szamlunnepnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='u'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlunnepnap++;
				}
				if ($szamlunnepnap!=0) $betuszin="#FF0000";

// unnepnap kieg. vege

// kotelezo szabi kieg.

	   			$szamlunnepnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='ksz'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlunnepnap++;
				}
				if ($szamlunnepnap!=0) $naphatterszin="#ff7a7a"; 

// kotelezo szabi kieg. vege

// kotelezo munkanap kieg.

	   			$szamlmnap=0;
	   			$lekerdez = mysql_query ("SELECT * FROM naptar WHERE n_datum='$sqlbasedtime' AND n_statusz='mn'") or die (mysql_error());
				while ($sor = mysql_fetch_array ($lekerdez)) { 
					$szamlmnap++;
				}
				if ($szamlmnap!=0) $betuszin="#00000";

// kotelezo munkanap kieg.

	   			if ($om[$knap]=="i") $naphatterszin="#C8DDC6"; 


	   			$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1" color="'.$betuszin.'">
	   					<b><i> ';
	   			$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
	   					<b><i> ';
	   			if ($cegoszlopossz[$cknap]!=0) 
	   				{ 
	   				$kiirkep.=$cegoszlopossz[$cknap]; 
	   				$pcegoszlopossz=str_replace(".", ",", $cegoszlopossz[$cknap]); 
	   				$kiirprint.=$pcegoszlopossz; 
	   				}
	   			$osszosszora=$osszosszora+$cegoszlopossz[$cknap];
	   			$kiirkep.='</i></b></font></td>';
	   			$kiirprint.='</i></b></font></td>';
	   			}
	   		$kiirkep.='<td width="25" height="25" valign="center" align="center" bgcolor="'.$naphatterszin.'"><font size="1" color="#009900">
	   					<b><i>'.$osszosszora.'</i></b></font></td></tr>';
	   		$kiirkep.='</table>';

	   		$posszosszora=str_replace(".", ",", $osszosszora);

	   		$kiirprint.='<td width="25" height="25" valign="center" align="center"><font size="1">
	   					<b><i>'.$posszosszora.'</i></b></font></td></tr>';
	   		$kiirprint.='</table>';

	   	//$kiirprint=str_replace(".5", ",5", $kiirprint);

		$printfile='print/'.$sid.'.txt';
		if (file_exists($printfile)) unlink ($printfile);
		touch ($printfile);
		$fp = fopen($printfile, 'w');
		fwrite($fp, $kiirprint);
		fclose($fp);

		print ('<a href="print.php" target="print"><img border="0" width="50" src="img/print.png"></a><br /><br />');


	   	print ($kiirkep); //itt irja ki az egesz riportot!
			 
	   	}
		else
		{
		print ('ejjno');
		}
	print ($riportvege);
	} 

	?>