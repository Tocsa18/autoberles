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
		//include('php/levelezes.php');
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
	<title>BérAutó24</title>
	<link rel="stylesheet" type="text/css" href="css/stilus.css">

	<!--<script type="text/javascript" src="js/import/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="js/import/jquery/jQuery_3_7_1.js"></script>
	<script type="text/javascript" src="js/sajat.js"></script>-->

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<!--<script type="text/javascript" src="js/import/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="js/import/jquery/jQuery_3_7_1.js"></script>-->
	<script type="text/javascript" src="js/mezo_check.js"></script>
</head>
<body>

	<?php 
		include('html/vezerlopult.html');
		if (isset($_GET['menupont']))
		{
		switch ($_GET['menupont']) {
			case 'jarmuvek' :  	include('php/jarmuvek.php');
								include('html/jarmuvek.html');
			 					break;
			default : 			break;
			}
		}
		if(isset($_GET['menupont']) && $_GET['menupont'] == 'jarmuvek')
		{
			include('php/jarmuvek.php');
		}

	if ($belepve == true )
	{if (isset($_GET['menupont']) && $_GET['menupont'] == 'logout')
		 {include('html/kilepes.html');
		   	if ($_SERVER['REQUEST_METHOD'] === 'POST') 
		   	{
	        $belepes->_kilepes();
	        header("Location: index.php");
	        exit;
	    	}
		 }
		else 
		 {
			include('html/vezerlopult.html');
			if(isset($_GET['menupont']) && $_GET['menupont'] == 'jarmuvek')
			{
				include('php/jarmuvek.php');
			}
		}
	}
	else {include('html/belep.html');}

	?>
</body>
</html>