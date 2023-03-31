<?php

/**
 * Kontroler pro zpracovani zaznamu stavajiciho pojistence
 */
class StavajiciPojistenecKontroler extends Kontroler
{

    public function zpracuj(array $parametry) : void
    {
		$this->hlavicka = array(
			'titulek' => 'Editovat pojištěnce',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná rozšířená verze'
		);

		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();

		// nesmi chybet parametr URL, resp. musi to byt cislo
		if (!empty($parametry[0]) && is_numeric($parametry[0])) {
			$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
			// Pokud dany parametr neodpovida ID pojistence, přesměrujeme na ChybaKontroler
			if (!$pojistenec)
				$this->presmeruj('chyba');
		} else {
			$this->presmeruj('chyba');
		}

		// nacteni zaznamu daneho pojistence
		$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));

		// Je odeslán formulář
		if ($_POST) 
		{
			try
			{
				$validator = new Validator();
				$validator->validujPojistence();
				// nastaveni relevantnich klicu
				$klice = array('jmeno', 'prijmeni', 'email', 'telefon', 'ulice_cp', 'mesto', 'psc');
				// z globalni promenne se vyberou relevantni hodnoty podle nastavenych klicu
				$pojistenec = array_intersect_key($_POST, array_flip($klice));
				// Uložení pojištěnce do DB
				$spravcePojistencu->aktualizujPojistence($pojistenec, array($parametry[0]));
				$this->pridejZpravu(TypZpravy::Uspech, 'Záznam pojištěnce ' . $_POST['jmeno'] . ' ' . $_POST['prijmeni'] . ' byl v evidenci aktualizován.');
				// $_SESSION['uspech'] = 'Pojištěnec ' . $_POST['jmeno'] . ' ' . $_POST['prijmeni'] . ' byl aktualizován.';
				$this->presmeruj('pojistenci');
			}
			catch (ChybaUzivatele $chyba)
			{
				$this->pridejZpravu(TypZpravy::Chyba, $chyba->getMessage());
			}
		}

		//predani promennych do pohledu
		$this->data['pojistenec'] = $pojistenec;

		$this->pohled = 'stavajici-pojistenec';
    }
}