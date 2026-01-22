<?php

	// Ez egy függvény, ami megkeresi az aktiválás könyvtár 
    // alapján az oldal gyökérkönyvtárához vezető utat.
	function gyoker()
	{
		
		// Az aktualis_hely változóba feltöltjük a munkakönyvtárat.
		$aktualis_hely = getcwd();

		// Ez a parancs True értékkel tér vissza, ha 
		// létezik a php könyvtár a munkakönyvtáron belül.
		
		// is_dir($aktualis_hely."/php");
		
		while (!is_dir($aktualis_hely.'/php'))
		{
			$aktualis_hely .= '/..';
		}
		// Az összeállított útvonalat visszaadjuk
		
		return $aktualis_hely;
		
		//return realpath(__DIR__ . '/..');
	}

	function baseURL()
	{
		global $baseURL;
		return $baseURL;
	}

?>