<?php

/**
 * Kontroler pro vytvoreni zaznamu noveho pojistneho produktu
 */

class PridatPojisteniKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Přidat pojištění',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná rozšířená verze'
		);
		
		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();

		// nesmi chybet parametr URL (ID pojistence, kteremu se pojisteni prirazuje), resp. musi to byt cislo
		if (!empty($parametry[0]) && is_numeric($parametry[0])) {
			$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
			// Pokud dany parametr neodpovida ID pojistence, přesměrovat na ChybaKontroler
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
				$validator->validujPojisteni();
				//nastaveni pole pro intersect
				$pojisteni = array (
					'poj_produkt' => $_POST['poj_produkt'],
					'poj_castka' => $_POST['poj_castka'],
					'predmet_poj' => $_POST['predmet_poj'],
					'platnost_od' => FormatHelper::datumDoDB($_POST['platnost_od']),
					'platnost_do' => FormatHelper::datumDoDB($_POST['platnost_do']),
					'pojistenec_id' => $parametry[0],
				);
				// vlozeni noveho zaznamu do databaze
				$spravcePojisteni->vlozNovePojisteni($pojisteni);
				// ulozeni zpravy do session
				$this->pridejZpravu(TypZpravy::Uspech, 'Nový pojistný produkt ' . $_POST['poj_produkt'] . ' byl zapsán do evidence!');
				$this->presmeruj("detail-pojistence/{$parametry[0]}");
			}
			catch (ChybaUzivatele $chyba)	
			{
				$this->pridejZpravu(TypZpravy::Chyba, $chyba->getMessage());
			}
		}

		//predani promennych do pohledu
		$this->data['pojistenec'] = $pojistenec;

		$this->pohled = 'pridat-pojisteni';
	}
}