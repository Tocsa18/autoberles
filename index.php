<?php
	// - Minden PHP állomány legelejére kell, csak egyszer!
	//   tehát minden ebben a PHP állományban behívott PHP állomány
	//   megörökli ezt a beállítást!
	header('Content-type: text/html; charset=utf-8');
	date_default_timezone_set("Europe/Budapest");
	//munkamenet inditása
	session_start();
	// Az oldal működéséhez szükséges alapvető konfigurációs beállítások behívása
	include('config/config.inc');
	// Az alternatív útvonal problémáját feloldó utvonal.php állomány behívása.
	include('php/utvonal.php');
	// Behívjuk a hibát naplózó php-t
	include('php/naplo.php');
	
	try{
		// Minden objektum által elérhető függvényeim
		//include('php/fuggvenyek.php');
		// echo(veletlenkaraktersor("user-").date('Y').date('m').date('d')); <-- Itt egy minta, így teszteltem
		// Adatbázis kapcsolat felépítéséhet szükséges PHP
		include('php/adatbaziskapcsolat.php');
		include('php/munkamenet.php');
		
//-------------------------------------------------------------------//
		include('php/levelezes.php');
//-------------------------------------------------------------------//		

		include('php/belepes.php');
		// Naplózzuk az oldal betöltődését
		$naplo->_bejegyez("Az oldal újratöltődött.");
	}
	catch (\ERROR $weblaphiba) {$naplo->_bejegyez(basename($weblaphiba->getFile()).', sor: '.$weblaphiba->getLine().', hiba: '.$weblaphiba->getMessage());}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Aktiváló link készítése</title>
	<script type="text/javascript" src="js/import/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="js/import/jquery/jQuery_3_7_1.js"></script>
	<script type="text/javascript" src="js/sajat.js"></script>

</head>
<body>
	<?php 

	 if ($belepve == true )
	  { if (isset($_GET['logout']))
		 { 
		 
		 }
		else 
		 {
		 	if (isset($_GET['menupont']))
		 	 {$menupont = $_GET['menupont'];}
		 	else 
		 	 {$menupont = '';}	
		 	
		 	include('html/vezerlopult.html');
		 	switch ($menupont) {
		 		case 'felhasznalok' :
		 							  // Beillesztjük a felhasználók kezeléséért felelelős objektumot!
 									  include('php/felhasznalok.php');
		 							  include('html/felhasznalok.html');
		 							  break;
		 		case 'ujfelhasznalo' : include('php/felhasznalok.php');
		 							   include('html/felhasznalo.html');

		 							   break;
		 		case 'felhasznaloment' : include('php/felhasznalok.php');
		 							     if ($felhasznalokezeles->felhasznalo_ment() == true)
		 							      {include('html/felhasznalok.html');}
		 							     else {include('html/felhasznalo.html');}
		 							     break;
		 		case 'szerkesztfelhasznalo' : include('php/felhasznalok.php');
		 									  $felhasznalokezeles->felhasznalo_szerkeszt($_POST['id']);
		 									  include('html/felhasznalo.html');
		 									  break;
		 		case 'felhasznalofrissit' : include('php/felhasznalok.php');
		 									if ($felhasznalokezeles->felhasznalo_frissit($_POST['id']) == true)
		 										{include('html/felhasznalok.html');}
		 									else
		 									{
		 										include('html/felhasznalo.html');
		 									}
		 									break;
		 		case 'torolfelhasznalo' :   include('html/felhasznaloktorles.html');
		 									break;
		 		case 'felhasznalotorles' :  include('php/felhasznalok.php');
		 									$felhasznalokezeles->felhasznalo_torol($_POST['id']);
		 									include('html/felhasznalok.html');
		 									break;
		 		case 'aktivfelhasznalo' :   include('php/felhasznalok.php');
		 									$felhasznalokezeles->felhasznalo_aktivalas($_POST['id']);
		 									include('html/felhasznalok.html');
		 									break;

		 		case 'tartalmak' :   		include('php/tartalom.php');
		 									//include('html/tartalmak.html');
		 								    break;


		 		default : 				    break;
		 	}
		 }
	  }

	 else 
	 {//ha nincs belepve, akkor is izsgalom a menupont megletet mert belepeshez szukseges
	 	if (isset($_GET['menupont']))
		 	 {$menupont = $_GET['menupont'];}
		 	else 
		 	 {$menupont = '';}
		 	switch ($menupont) {
		 		case 'regisztracio':include('php/felhasznalok.php');
		 							include('html/felhasznalo.html');
		 							echo('<a href="index.php">Vissza a főoldalra</a>');
		 							break;

		 		case 'felhasznaloment':include('php/felhasznalok.php');
		 							if($felhasznalokezeles->felhasznalo_ment()==true)
		 							{include('html/regisztraciovege.html');}
		 							else include('html/felhasznalo.html');
		 							break;


		 		case 'aktivalas':include('php/felhasznalok.php');
		 							if($felhasznalokezeles->felhasznalo_aktivalas_linkbol()==true)
		 							{include('html/regisztracioaktiv.html');}
		 							else include('html/regisztracionemaktiv.html');
		 							break;

		 		case 'belepes':		include('html/belep.html');
		 							break;

		 		case 'kezdolap':	
		 		default 			:include('php/tartalom.php');
		 							include('html/oldal.html');
		 							break;
		 	}


	 	}
	?>
</body>
</html>