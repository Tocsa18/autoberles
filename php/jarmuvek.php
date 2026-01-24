<?php

$autok = new autok($db_kapcsolat,$naplo);

		if (isset($_GET['jarmu']))
		{
		switch ($_GET['jarmu']) {
			case 'uj'		:   include('html/jarmufelvetel.html');
								break;
			case 'jarmuvek' :  	include('html/jarmuvek.html');
			 					break;
			case 'ment'  :   	if ($autok->ment() == true)
								{
									include('html/jarmuvek.html');
								}
								else
								{
									include('html/jarmufelvetel.html');
								}
								break;
			case 'szerkeszt' :  if (isset($_GET['auto_id']))
    						 	{$autok->szerkeszt($_GET['auto_id']);
    						  	include('html/jarmufelvetel.html');}
    							else {include('html/jarmuvek.html');}
								break;
			case 'modosit'  :  if (isset($_GET['auto_id']))
    						 	{if ($autok->modosit($_GET['auto_id']) == true) 
    						   {include('html/jarmuvek.html');}
    						  	else {include('html/jarmufelvetel.html');} 
    						 	}
    							else {include('html/jarmuvek.html');} 
    						break;
			default : 			break;
			}
		}
		else {if (!isset($_POST['auto_id']))
 	    {include('html/jarmuvek.html');}}

class autok {

 	private $naplo;
 	private $db_kapcsolat;

 	// Adatbázis mezők adattagjai.
 	public $auto_id;
 	public $kategoria_id;
 	public $marka;
 	public $modell;
 	public $evjarat;
 	public $alvazszam;
 	public $rendszam;
 	public $aktualis_telephely_id;
 	public $allapot;
 	public $napi_dij;


 	// Megmondja, hogy milyen műveletet hajtok éppen végre!
 	public $muvelet;

 	// - Üzenet, amit a felhasználónak szánok
 	//   lehet ez hibaüzenet is!
 	public $uzenet;
 	
 	public function __construct($db_kapcsolat,$naplo = null) {
 		// - A paraméterben megadott "objektumokat" itt
 		//   adom át a helyi változóknak, ami hatására a 
 		//   változók objektumok lesznek!
 		$this->db_kapcsolat = $db_kapcsolat;
 		$this->naplo = $naplo;
 		$this->naplo->_bejegyez(__CLASS__.' osztály létrejött.');
		// - Beállítom a müveletet, azért, mert a termek.html FORM elemének
		//   az action url-jét ez alapján fogom változtatani 
		$this->muvelet = 'insert';
 	}

 	public function __destruct() {
 		$this->naplo->_bejegyez(__CLASS__.' osztály megsemmisült.');
 	}

 	public function _autok_lista() {

 		// - Kell egy változó, amiben a lista HTML részét tárolom
		$HTMLSorok = "";

		// - A termékeket akarom listázni, ezek adatbázisban vannak
		//   ezért elkészítem az SQL lekérdezést!

		$SQLlekerdezes = "SELECT * FROM autok";

		// Példa a naplózásra. Kiírom naplóba a lekérdezést
		$this->naplo->_bejegyez($SQLlekerdezes);

		// Lefuttatjuk az SQL lekérdezést!
		$SQLeredmeny = mysqli_query($this->db_kapcsolat->_kapcsolat(),$SQLlekerdezes);

		// - A futtatást követően van egy központi változóz, ahonnan
		//   kinyerhetem azt, hogy volt-e hibám?
		if (empty($sqlhiba))
		{
			// - Amennyiben az $sqlhiba üres, 
			//   abban az esetben fel kell dolgoznom
			//   az eredményhalmazt!
			while ($egysor = mysqli_fetch_assoc($SQLeredmeny)) 
			{
				$editcommand = "index.php?menupont=jarmuvek&jarmu=szerkeszt&auto_id=".$egysor['auto_id'];
				$delcommand = "index.php?menupont=jarmuvek&jarmu=torol&auto_id=".$egysor['auto_id'];

				$HTMLSorok .= "<tr>";
				$HTMLSorok .= "<td>".$egysor['marka']."</td>";
				$HTMLSorok .= "<td>".$egysor['modell']."</td>";
				$HTMLSorok .= "<td>".$egysor['evjarat']."</td>";
				$HTMLSorok .= "<td>".$egysor['alvazszam']."</td>";
				$HTMLSorok .= "<td>".$egysor['rendszam']."</td>";
				$HTMLSorok .= "<td>".$egysor['allapot']."</td>";
				$HTMLSorok .= "<td>".$egysor['napi_dij']."</td>";
				$HTMLSorok .= ' <td><a href="'.$editcommand.'"><i class="fa-solid fa-pen-to-square"></i></a><a href="'.$delcommand.'"><i class="fa-solid fa-trash"></i></a></td>';
				$HTMLSorok .= "</tr>";
			}
		}
		else {
			// - Nem volt üres az sqlhiba, ezért elküldöm a naplóba ahibát!
			$this->naplo->_bejegyez($sqlhiba);
		}
		// Itt adom vissza a HTML sorokat a lapnak.
		return $HTMLSorok;
 	}
 	public function ment() {

		// - Beállítom a müveletet, azért, mert a termek.html FORM elemének
		//   az action url-jét ez alapján fogom változtatani 
		$this->muvelet = 'insert';

		// - Be kell gyűjtenem a POST-olt adatokat, de figyelnem kell
		//   arra, hogy létezik-e a POST! Abban az esetben, ha nem létezik
		//   (else ág) a változó értékét feltöltöm semmivel! A kötelző érték
		//   vizsgálatnál majd kibukik, ha nem kaptam adatot!
		if (isset($_POST['marka'])) {
			$this->marka = $_POST['marka'];} else {$this->marka = '';}
		if (isset($_POST['modell'])) {
			$this->modell = $_POST['modell'];} else {$this->modell = '';}
		if (isset($_POST['evjarat'])) {
			$this->evjarat = $_POST['evjarat'];} else {$this->evjarat = '';}
		if (isset($_POST['alvazszam'])) {
			$this->alvazszam = $_POST['alvazszam'];} else {$this->alvazszam = '';}
		if (isset($_POST['rendszam'])) {
			$this->rendszam = $_POST['rendszam'];} else {$this->rendszam = '';}
		if (isset($_POST['napi_dij'])) {
			$this->napi_dij = $_POST['napi_dij'];} else {$this->napi_dij = '';}

		// Feltételezem, hogy minden adat megvan ezért a mentés sikeres lesz!
		$sikeresmentes = true;	

		// Kötelező érték vizsgálata
		if (empty($this->marka) || empty($this->modell) || empty($this->evjarat) ||
	 	    empty($this->alvazszam) || empty($this->rendszam) || empty($this->napi_dij)) 
			{$this->uzenet = 'Kérem tötlse ki a pirossal jelölt mezőket!';
			 $sikeresmentes = false;}

		// - Cask akkor kezdek a mentéhez, ha a kötelező érték
		//   vizsgálat már lefutott, és a $sikeresmentes változó
		//   megengedi a mentést!
		if ($sikeresmentes == true)
		 {
			// - A termékeket akarom listázni, ezek adatbázisban vannak
			//   ezért elkészítem az SQL lekérdezést!

			$SQLlekerdezes = "INSERT INTO autok (marka,modell,evjarat,alvazszam,rendszam,napi_dij) 
							  VALUES ('$this->marka','$this->modell','$this->evjarat','$this->alvazszam','$this->rendszam','$this->napi_dij') ";

			// Lefuttatjuk az SQL lekérdezést!
			$SQLeredmeny = mysqli_query($this->db_kapcsolat->_kapcsolat(),$SQLlekerdezes);
		 }
		 // Eláruljuk a hívónak, hogy sikeres volt-e a mentés!
		 return $sikeresmentes;
	}
	public function szerkeszt($auto_id) {

		// - Beállítom a müveletet, azért, mert a termek.html FORM elemének
		//   az action url-jét ez alapján fogom változtatani 
		$this->muvelet = 'edit';
		$this->auto_id = $auto_id;

		// - A terméket akarom szerkeszteni, ezek adatbázisban vannak
		//   ezért elkészítem az SQL lekérdezést!

		$SQLlekerdezes = "SELECT * FROM autok WHERE auto_id = '$this->auto_id' ";

		// Lefuttatjuk az SQL lekérdezést!
		$SQLeredmeny = mysqli_query($this->db_kapcsolat->_kapcsolat(),$SQLlekerdezes);

		// - A futtatást követően van egy központi változóz, ahonnan
		//   kinyerhetem azt, hogy volt-e hibám?
		if (empty($sqlhiba))
		{
			// - Amennyiben az $sqlhiba üres, 
			//   abban az esetben fel kell dolgoznom
			//   az eredményhalmazt!
			while ($egysor = mysqli_fetch_assoc($SQLeredmeny)) 
			{
				$this->marka = $egysor['marka'];
				$this->modell = $egysor['modell'];
				$this->evjarat = $egysor['evjarat'];
				$this->alvazszam = $egysor['alvazszam'];
				$this->rendszam = $egysor['rendszam'];
				$this->napi_dij = $egysor['napi_dij'];
			}
		}
		else {
			// - Nem volt üres az sqlhiba, ezért elküldöm a naplóba ahibát!
			$this->naplo->_bejegyez($sqlhiba);
		}
 	}
 	public function modosit($auto_id) {

		// - Beállítom a müveletet, azért, mert a termek.html FORM elemének
		//   az action url-jét ez alapján fogom változtatani 
		$this->muvelet = 'update';
		$this->auto_id = $auto_id;

		// - Be kell gyűjtenem a POST-olt adatokat, de figyelnem kell
		//   arra, hogy létezik-e a POST! Abban az esetben, ha nem létezik
		//   (else ág) a változó értékét feltöltöm semmivel! A kötelző érték
		//   vizsgálatnál majd kibukik, ha nem kaptam adatot!
		if (isset($_POST['marka'])) {
			$this->marka = $_POST['marka'];} else {$this->marka = '';}
		if (isset($_POST['modell'])) {
			$this->modell = $_POST['modell'];} else {$this->modell = '';}
		if (isset($_POST['evjarat'])) {
			$this->evjarat = $_POST['evjarat'];} else {$this->evjarat = '';}
		if (isset($_POST['alvazszam'])) {
			$this->alvazszam = $_POST['alvazszam'];} else {$this->alvazszam = '';}
		if (isset($_POST['rendszam'])) {
			$this->rendszam = $_POST['rendszam'];} else {$this->rendszam = '';}
		if (isset($_POST['napi_dij'])) {
			$this->napi_dij = $_POST['napi_dij'];} else {$this->napi_dij = '';}

		// Feltételezem, hogy minden adat megvan ezért a mentés sikeres lesz!
		$sikeresmentes = true;	

		// Kötelező érték vizsgálata
		if (empty($this->marka) || empty($this->modell) || empty($this->evjarat) ||
	 	    empty($this->alvazszam) || empty($this->rendszam) || empty($this->napi_dij)) 
			{$this->uzenet = 'Kérem tötlse ki a pirossal jelölt mezőket!';
			 $sikeresmentes = false;}

		// - Cask akkor kezdek a mentéhez, ha a kötelező érték
		//   vizsgálat már lefutott, és a $sikeresmentes változó
		//   megengedi a mentést!
		if ($sikeresmentes == true)
		 {
			// - A termékeket akarom listázni, ezek adatbázisban vannak
			//   ezért elkészítem az SQL lekérdezést!

			$SQLlekerdezes = "UPDATE autok 
							  SET	 marka = '$this->marka',
							  		 modell = '$this->modell',
							  		 evjarat = '$this->evjarat',
							  		 alvazszam = '$this->alvazszam',
							  		 rendszam = '$this->rendszam',
							  		 napi_dij = '$this->napi_dij'
							  WHERE  auto_id = '$this->auto_id' ";

			// Lefuttatjuk az SQL lekérdezést!
			$SQLeredmeny = mysqli_query($this->db_kapcsolat->_kapcsolat(),$SQLlekerdezes);
		 }
		 // Eláruljuk a hívónak, hogy sikeres volt-e a mentés!
		 return $sikeresmentes;
	}
}
?>