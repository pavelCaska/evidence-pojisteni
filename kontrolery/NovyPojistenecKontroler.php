<?php

/**
 * Kontroler pro vytvoreni zaznamu noveho pojistence
 */
class NovyPojistenecKontroler extends Kontroler
{

    public function zpracuj(array $parametry) : void
    {
		$this->hlavicka = array(
			'titulek' => 'Nový pojištěnec',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná rozšířená verze'
		);

		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		// Příprava prázdného pojištěnce
		$pojistenec = array(
			'jmeno' => '',
			'prijmeni' => '',
			'email' => '',
			'telefon' => '',
			'ulice_cp' => '',
			'mesto' => '',
			'psc' => '',
		);
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
				$pojistenec['registr_kod'] = rand(100100100, 900900900);
				// Uložení pojištěnce do DB
				$spravcePojistencu->vlozNovehoPojistence($pojistenec);
				// ulozeni zpravy do session
				$this->pridejZpravu(TypZpravy::Uspech, 'Pojištěnec ' . $_POST['jmeno'] . ' ' . $_POST['prijmeni'] . ' byl zapsán do evidence.');
				$this->presmeruj('pojistenci');
			} 
			catch (ChybaUzivatele $chyba) 
			{
				$this->pridejZpravu(TypZpravy::Chyba, $chyba->getMessage());
			}
		}

		//predani promennych do pohledu
		$this->data['pojistenec'] = $pojistenec;

		$this->pohled = 'novy-pojistenec';
    }
}