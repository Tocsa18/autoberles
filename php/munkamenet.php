<?php

try {
	 $munkamenettarolo = new munkamenet();
	} catch (\Error $internalError) { /* Az $internalError objektumból kiolvasom a hibaüzenetet */
		                         	  $hibanaplo->_bejegyez($internalError->getMessage()); 
        		                	}


 class munkamenet {

 	// Egy tömb, melyet a munkamenet tárolóban fogok
 	// tárolni.
 	private $munkamenetadatok;

	/* - Konstruktor - a $this szóval utalunk a saját 
		 fentebb létrehozott változóra! */
 	public function __construct()
 	{
 		// - Létrehozom a munkamenethez kapcsolódó 
 		//   tömböt, amely tárolni fogja a munkamenet adatait!
 		$this->munkamenetadatok = array("belepve" => "false");

 		// - Ellenörzöm, hogy létezik-e a munkamenet tárolóban
 		//   a tömb, melyben az oldal "dolgait" tárolom.
 		if (!isset($_SESSION['munkamenetadatok']))
 		{
 			// - Ebben az esetben nem létezik, ezért létrehozom
 			//   és, ha már itt vagyok, akkor be is állítom az 
 			//   alapértelmezett értékeket!
 			$this->munkamenetadatok['belepve'] = "false";

 			// Most elteszem a tömböt a munkamenet tárolóba
 			$_SESSION['munkamenetadatok'] = $this->munkamenetadatok;
 		}
 		else {
 				// Ebben az esetben a "munkamenetadatok" tömb létezik,
 				// ezért visszakérem!
 			    $this->munkamenetadatok = $_SESSION['munkamenetadatok'];
 			 }
 	}

 	// Destruktor
   	public function __destruct() 
   	{
   	}

   	// - Függvény, ami megmondja, hogy a keresett paraméternek
   	//   mi az értéke!
   	public function parameter_olvas($pname, $pdefvalue = '')
   	{
   		if (isset($this->munkamenetadatok[$pname]))
   		{
   			return $this->munkamenetadatok[$pname];
   		}
   		else {return $pdefvalue;}
   	}


   	// - Függvény, ami beírja a munkamenet tárolóba a
   	//   megadott paraméterhez a megadott értéket!
   	public function parameter_ir($pname, $pvalue)
   	{
		$this->munkamenetadatok[$pname] = $pvalue;
		// - Visszaírom a szerverbe az adatokat.
		$_SESSION['munkamenetadatok'] = $this->munkamenetadatok;

   	}


 }

?>