<?php

/**
 * Kontroler pro registraci
 */
class RegistraceKontroler extends Kontroler
{
    public function zpracuj(array $parametry) : void
    {		
		// Hlavička stránky
		$this->hlavicka['titulek'] = 'Registrace';
		
		if ($_POST)
		{
			try
			{
				$spravceUzivatelu = new SpravceUzivatelu();
				$spravceUzivatelu->registruj(
					$_POST['jmeno'], 
					$_POST['prijmeni'], 
					$_POST['email'], 
					$_POST['registr_kod'], 
					$_POST['prezdivka'], 
					$_POST['heslo'], 
					$_POST['heslo_znovu']); 
				$spravceUzivatelu->prihlas($_POST['prezdivka'], $_POST['heslo']);
				$this->pridejZpravu(TypZpravy::Info, 'Registrace proběhla úspěšně. Jste přihlášen(a), můžete začít používat aplikaci.');
				$this->presmeruj('pojistenci');
			}
			catch (ChybaUzivatele $chyba)
			{
				$this->pridejZpravu(TypZpravy::Chyba, $chyba->getMessage());
			}
		}

		// Nastavení šablony
		$this->pohled = 'registrace';
    }
}