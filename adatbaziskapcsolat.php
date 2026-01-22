<?php
 /* EZ már egy osztály szintű objektum orientált példa. */

 $db_kapcsolat = new adatbazis_kapcsolat($database_host,$datebase_user,$datebase_password,$datebase_db);
 // Ha a felhasználónév és jelszó más lenne, akkor még ez kellene!
 // $db_kapcsolat->_beallit('felhasznalo_nev','felhasznalo_jelszo');
 $db_kapcsolat->_kapcsolodas();

 class adatbazis_kapcsolat
 {
 	
 	// Változók melyeket nem lehet kívülről elérni
	// Egy kapcsolatot felépítő Connection objektum, és annak 
	// a konfigurációját tároló változókra!

	private $host;
	private $user;
	private $password;
	private $db;

	// Definiálunk egy objektumot, ami kapcsolódni fog az adatbázis kiszolgálóhoz!
	private $connection;


 	/* - Konstruktor - a $this szóval utalunk a saját 
 		 fentebb létrehozott változóra! */
 	public function __construct($dbh,$dbu,$dbp,$dbdb)
 	{
 		// - Itt beleégettem az adatbázis kapcsolat paramétereit, de a vizsgaremekben
 		//   kívülről elérhetővé kell majd tenni, hogy könnyebb legyen a vizsgaremek
 		//   publikálása külső kiszolgálóra!

		$this->host = $dbh;
		$this->user = $dbu;
		$this->password = $dbp;
		$this->db = $dbdb; // <- A "autokolcsonzo" adatbázishoz kapcsolódunk!

 	}

 	// Destruktor
   	public function __destruct() {
   	}

 	// Kívülről lehessen beállítani a felhasználót és jelszavát
 	public function _beallit($user,$password)
 	{
 		$this->user = $user;
		$this->password = $password;
 	}

 	// Kívülröl utasítjuk a kapcsolódásra!
 	public function _kapcsolodas()
 	{
		// Létrehozzuk az objektumot, ami kapcsolódni fog az adatbázis kiszolgálóhoz!
		$this->connection = mysqli_connect($this->host,$this->user,$this->password,$this->db);
 	}

 	// - Ezen a függvényen keresztül elérjük az osztály PRIVATE objetumát!
 	//   FIGYELEM! Ezzel a megoldással az objetum csak olvasható!
 	public function _kapcsolat()
 	{
 		return $this->connection;
 	}

 }


?>