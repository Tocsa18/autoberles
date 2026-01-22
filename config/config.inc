<?php

	// Ide gyűjtöm az oldal működéséhez szükséges alapvető konfigurációs beállításokat!
	global $baseURL;
	$baseURL="http://127.0.0.1/backend/Backend-Link-PDO";
	
	$database_host = 'localhost';
	$datebase_user = 'weblap';
	$datebase_password = 'weblap';
	$datebase_db = 'kolcsonzo';
 
	$log_enabled = true;

	$mail_host="smtp.gmail.com";
	$mail_user="zoltangevesi81@gmail.com";
	$mail_psw="fgvg fdyo oevv ushl";

	$connectionstring = "mysql:host=".$database_host.";dbname=".$datebase_db.";charset=utf8mb4";

	/* $connectionstring = [PDO::AFTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					      PDO::AFTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					      PDO::AFTR_EMULATE_PREPARES => false,]; */
	
	$connectionoptions = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					   	  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					      PDO::ATTR_EMULATE_PREPARES => false,];
?>