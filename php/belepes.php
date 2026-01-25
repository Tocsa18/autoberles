<?php 

 $belepve = false;

 $belepes = new belep();

 if (isset($_POST['usr']) && isset($_POST['psw']))
 	{
 		try {
   			 $belepve = $belepes->_belepes($_POST['usr'],MD5($_POST['psw']));
   			} catch (\Error $internalError) { /* Az $internalError objektumból kiolvasom a hibaüzenetet */
            		                         $hibanaplo->_bejegyez($internalError->getMessage()); 
                    		                }
    }
    else
    {
    	$belepve = $belepes->_belepve();
    } 

    if (isset($_POST['logout']))
    {
    	$belepve = $belepes->_kilepes();
    }
class belep {

 	// - Az SQL parancsokat is mindig egy központi változóban tárolom,
 	//   a megoldás nem célszerű, viszont kiküszöböli az SQL parancsok összekeveresését,
 	//   mivel minden parancs felülírja az előző parancsot!
 	private $sqlCommand;

 	// FONTOS! A belépés sikerességét jelző logikai változó!
 	private $sikeresbelepes;

 	/* - Konstruktor - a $this szóval utalunk a saját 
 		 fentebb létrehozott változóra! */
 	public function __construct()
 	{
 		// - Ha nagyon biztosra akarok menni, akkor ide teszek
 		//   egy nagyon felesleges lépést :)
 		$this->sqlCommand = "";

 		// - Nagyon pesszimista vagyok!  A "sikeresbelepes" változót
 		//   biztonsági okokból azonnal "false" értékre állítom!
 		$this->sikeresbelepes = false;

 		// - Vissza próbálom olvasni a munkamenet tároló adatait
 		global $munkamenettarolo;
 		if ($munkamenettarolo->parameter_olvas('belepve','false') == 'true')
 			{$this->sikeresbelepes = true;}
 	}

 	// Destruktor
   	public function __destruct() 
   	{
   	}

   	function _belep($loginname, $password) {
		// Elkészítem a lekérdezést!
   		/* - Ez a megoldás nem jó, mert sajnos a paraméterben kapott értékeket
   		     beilleszti az SQL parancsba! Megengedi az SQL INJECTION támadási formát!
   		     Nem csak megengedi, lehetőséget ad rá!

		   - SQL INJECTION:
   		     A beérkező paraméter nem felhasználónevet és jelzót tartalmaz! A támadó
   		     SQL parancsokat gépel be, amivel nem megengedett adatbázis műveleteket hajt végre!

   		   - Mi a jó megoldás?
   		     PDO használata!  

   		*/
   		
   		// - Mivel SELECT COUNT a parancs, ha nincs a feltételnek megfelelő
   		//   sor az adatbázisban, akkor is lesz egy visszatérési sor -> tatlalat 0 lesz!   
   		$this->sqlCommand = "SELECT COUNT(id) as talalat 
   							 FROM   user
   							 WHERE  loginname = '$loginname' 
   							 		AND
   							 		password = '$password' ";

   		// Végrehajtom a lekérdezést úgy, hogy a connection segítségével elküldöm a szervernek!
   		global $db_kapcsolat;
   		// - A mysqli_query függvény által összeszedett eredmény halmazt (adatbázis sorai)
   		//   az $SQLResult változóba tesszük! 
   		$SQLResult = mysqli_query($db_kapcsolat->_kapcsolat(),$this->sqlCommand);

   		// A futtatás után megkérdezzük, hogy van-e hibánk?
		$sqlerror = mysqli_error($db_kapcsolat->_kapcsolat());

		if (empty($sqlerror))
		    {
				// - Az $SQLResult változóban található listát lépésről lépésre feldolgozom!
		   		if (mysqli_num_rows($SQLResult) > 0)
				{
					while ($row = mysqli_fetch_assoc($SQLResult))
					{
						// Megvizsgáljuk, hogy a "talalat" értéke mennyi!
						// - Amennyiben "klónozással" próbálkozik a támadó, akkor
						//   1-nél több eredményt kapunk! Mivel a felhasználónév 
						//   egyedi, abban az esetben csak 1 lehet a helyes érték!
						if ($row['talalat'] == 1)
							{$this->sikeresbelepes = true;}
						else {$this->sikeresbelepes = false;} // <- Elvileg már a konstruktorban elintéztem!
					}
				} else {/* - Bármilyen SQL-s akció után, ha egyetlen elem sincs a 
							 válasz táblában a belépés lehetőségét azonnal elvesszük!
							 Így a támmadó SQL mókolással sem tud illetéktelenül belépni! */
			  				 $this->sikeresbelepes = false;}
		    }
		else {/* - Bármilyen hiba keletkezett, a belépés lehetőségét
			       azonnal elvesszük! Így a támmadó hiba kikényszerítésével
			       nem tud illetéktelenül belépni! */
			  $this->sikeresbelepes = false;
			  $hibanaplo->_bejegyez($sqlerror);}
      
      // - A függvény visszatérési értéke!
	  return $this->sikeresbelepes;
   	}

     /* Osztály interfész */
     public function _belepes($loginname, $password) {
         /* Használni fogom a hibanaplót */
         global $hibanaplo;
         try { 
         		// - Meg kell kérdezni a munkamenet kezeléséért 
         		//   felelős objektumot, hogy mi a manó van? 
         		//   Pontosabban, a konstruktor már lekérdezi, ezért
         		//   megvizsgáljuk, hogy a $this->sikeresbelepes milyen állapotban van!
         		if ($this->sikeresbelepes == true)
         			{return $this->sikeresbelepes;}
         		else {
         			  // - Mivel a $this->sikeresbelepes értéke false, ezért
         			  //   megkísérlem a belépést, aminek az eredményét a $this->sikeresbelepes
         			  //   változóban tárolom. Azért teszem oda, hoyg a munkamenet kezelésért
         			  //   felelős objektum egyes értékeit beállíthassam! 	
         			  $this->sikeresbelepes = $this->_belep($loginname, $password);
         			  // - A belépési kísérlet eredméyne alapján frissítem a 
         			  //   munkamenetet kezelő objektumot!
         			  global $munkamenettarolo;
         			  if ($this->sikeresbelepes == true)
         			    {$munkamenettarolo->parameter_ir('belepve','true');}
         			  else 
         			  	{$munkamenettarolo->parameter_ir('belepve','false');}
         			  // - Visszadaom a függvény hívójának a belépési kísérlet
         			  //   eredményét!	
         			  return $this->sikeresbelepes;}
             } catch (\Error $internalError) {$this->sikeresbelepes = false;
             								  $hibauzenet = basename($internalError->getFile()).', '.
                                                            $internalError->getLine().'. sor, '.
                                                            $internalError->getMessage();
                                              $hibanaplo->_bejegyez($hibauzenet);
                                          	  return $this->sikeresbelepes;}
      }

      public function _belepve() {
      	return $this->sikeresbelepes;
      }
      public function _kilepes()
      {
   		$this->sikeresbelepes = false;   
   		global $munkamenettarolo;
   		$munkamenettarolo->parameter_ir('belepve','false');
      	return $this->sikeresbelepes;
      }
}

?>