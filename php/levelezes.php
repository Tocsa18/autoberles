<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



	//behivjuk a PHPMailer csomagot
	require_once gyoker().'/import/phpmailer/src/Exception.php';
	require_once gyoker().'/import/phpmailer/src/PHPMailer.php';
	require_once gyoker().'/import/phpmailer/src/SMTP.php';


$levelkuld = new levelkuld($naplo,
                            $mail_host,
                            $mail_user,
                            $mail_psw);

//Mi az az alkamazas jelszó?
//-Kulso applikacio, csak ezzel a jelszoval ferhet hozza a google fiokhoz
//-csak ketfaktoros azonositas eseten mukodik
//-nincs kiteve elerhto menukent a google beallitasokban



class levelkuld{

	private $naplo;

	//levelkuldo objektum 
	private $postas;

	public function __construct($naplo,$m_host,$m_user,$m_password)
   {
   	  
      $this->naplo = $naplo;
      $this->naplo->_bejegyez(__CLASS__.' osztály létrejött');
      //letrehozom a levelkuldo objektumot
      $this->postas=new PHPMailer(true);
      //nyelv beallitasa
      $this->postas->CharSet= "UTF-8";
      // $this->postas->SMTPDebug = 3;
      //ahhoz h levelet tudjunkkuldeni, kell egy smtp kiszolgalo, ami kapcsolodni tud a kulonbozo kiszolgalokhoz pl.:Google
      $this->postas->isSMTP();
      //szuksegunk lesz a megfelel kiszolgalo beallitasokra
      $this->postas->Host=$m_host;
      $this->postas->Username=$m_user;
      $this->postas->Password=$m_password;
      //hitelesites beallitasa
      $this->postas->SMTPAuth=true;
      $this->postas->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
      $this->postas->Port=587;

   }

   // Destruktor
    public function __destruct() 
    {
      $this->naplo->_bejegyez(__CLASS__.' osztály megsemmisült');

    }

    public function levelkuldes($cimzett, $targy, $uzenet)
    {
      try {
      $ERRORStop=false;
      //elso amit ellenorzunk, hogy ne legyenek az alapveto paramterek uresek
      if(empty($cimzett)||empty($uzenet))
      {$this->naplo->_bejegyez("Helytelen címzett vagy üres üzenet!");
        $ERRORStop=true;}
        //hasznaljuk a PHP- rendszerbebeepitett levelezes uggvenyeket

      if (!filter_var($cimzett, FILTER_VALIDATE_EMAIL))
        {$this->naplo->_bejegyez("Helytelen címzett!");
        $ERRORStop=true;}

      if (!filter_var($this->postas->Username, FILTER_VALIDATE_EMAIL))
        {$this->naplo->_bejegyez("Helytelen felado (fiok)!");
        $ERRORStop=true;}
      //ha nincs hiba, mehet a level
        $this->naplo->_bejegyez($cimzett.$uzenet.$this->postas->Username);
        if($ERRORStop!=true)
        {
          //beallitjuk a feladot! ez szoktak a gyanus adathalasz leveleknel ugy beallitani h a cimzett azt lassa amit az adathalasz szeretne, pl. support@nicrosoft <-- szandekos nbetu
          //$this->postas->SetFrom($this->postas->Username,"ide a feladó neve jön, tehát a fiók tulajdonosának személyneve");
          //hozzaadjuk az igazi cimet
          $this->postas->addAddress($cimzett);
          //megadjuk a feladót, de miert?? Ezt hasznaljuk erre->noreply@google.com
          $this->postas->addReplyTo($this->postas->Username,"ide a feladó neve jön, tehát a fiók tulajdonosának személyneve");
          //level formatumanak beallitasa
          $this->postas->isHTML(true);
          //maga a level
          $this->postas->Subject=$targy;
          $this->postas->Body=$uzenet;
          //johet a kuldes,de fontos, h hibakezelve kuldjuk el az uzenetet, mert kulonben nem latjuk a kiszolgalo valaszat
          
            $this->postas->send();
          }
        }
          catch(\Error $mailerror){
            $this->naplo->_bejegyez($mailerror->getMessage);
          }


        }
  }

?>