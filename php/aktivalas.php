<?php

$aktivalas = new aktivalas($naplo);

class aktivalas {

	private $naplo;

	/* - Konstruktor - a $this szóval utalunk a saját 
 		 fentebb létrehozott változóra! */
	public function __construct($naplo)
 	{
 		$this->naplo = $naplo;
 		$this->naplo->_bejegyez("Aktiváló létrehozása");
 	}

 	// Destruktor
   	public function __destruct() 
   	{
   		$this->naplo->_bejegyez("Aktiváló megszüntetése");
   	}

}

?>