<?php

$autok = new autok($db_kapcsolat,$naplo);

class autok {

 	private $naplo;
 	private $db_kapcsolat;

 	// Adatbázis mezők adattagjai.
 	public $auto_id;
 	public $kategoria_id;
 	public $marka;
 	public $modell;
 	public $ev;
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
				// - A while ciklus az eredménhalmaz sorain egyesével végig lépdel!
				//   Az $egysor változóban lesz mindig az aktuális sor oszlopainak értékei.
				$HTMLSorok .= "<tr>";
				$HTMLSorok .= "<td>".$egysor['marka']."</td>";
				$HTMLSorok .= "<td>".$egysor['modell']."</td>";
				$HTMLSorok .= "<td>".$egysor['ev']."</td>";
				$HTMLSorok .= "<td>".$egysor['alvazszam']."</td>";
				$HTMLSorok .= "<td>".$egysor['rendszam']."</td>";
				$HTMLSorok .= "<td>".$egysor['allapot']."</td>";
				$HTMLSorok .= "<td>".$egysor['napi_dij']."</td>";
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
}
?>