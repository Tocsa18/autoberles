<?php
// Alap konfiguráció

$baseURL = "http://127.0.0.1/backend/autoberles";

// ADATBÁZIS ADATOK
$database_host = 'localhost';
$database_user = 'root';
$database_password = '';
$database_db = 'autokolcsonzo';

// LOG
$log_enabled = true;

// MAIL (FIGYELEM: éles projektben .env vagy gitignore!)
$mail_host = "smtp.gmail.com";
$mail_user = "zoltangevesi81@gmail.com";
$mail_password = "IDE_MAJD_APP_JELSZO"; // <-- ne tedd fel GitHubra!

// PDO-hoz előkészítve
$connectionString = "mysql:host=$database_host;dbname=$database_db;charset=utf8mb4";

$connectionOptions = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
