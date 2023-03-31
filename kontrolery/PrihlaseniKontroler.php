<?php

/**
 * Kontroler pro prihlaseni
 */
class PrihlaseniKontroler extends Kontroler
{
    public function zpracuj(array $parametry) : void
    {
		$spravceUzivatelu = new SpravceUzivatelu();
		// $uzivatel = $spravceUzivatelu->vratUzivatele();
		
		if (!empty($parametry[0]) && $parametry[0] == 'odhlasit') {
			$spravceUzivatelu->odhlas();
			$this->pridejZpravu(TypZpravy::Info, 'Odhlášení proběhlo úspěšně.');
			$this->presmeruj('prihlaseni');
		}
		
		// Hlavička stránky
		$this->hlavicka['titulek'] = 'Přihlášení';
		
		if ($_POST)
		{
			try
			{
				$spravceUzivatelu->prihlas($_POST['prezdivka'], $_POST['heslo']);
				$this->pridejZpravu(TypZpravy::Info, 'Přihlašení proběhlo úspěšně.');
				$this->presmeruj('pojistenci');
			}
			catch (ChybaUzivatele $chyba)
			{
				$this->pridejZpravu(TypZpravy::Chyba, $chyba->getMessage());
			}
		}

		// Nastavení šablony
		$this->pohled = 'prihlaseni';
    }
}