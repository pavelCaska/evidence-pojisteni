<?php

/**
 * Kontroler pro editaci stavajiciho pojisteneho produktu
 */
class EditovatPojisteniKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Editovat pojištěni',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná rozšířená verze'
		);
		
		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();
		$spravceUzivatelu = new SpravceUzivatelu();

		// nesmi chybet prvni ani druhy parametr URL, resp. musi to byt cislo
		if (!empty($parametry[0]) && is_numeric($parametry[0]) || !empty($parametry[1]) && is_numeric($parametry[1])) 
		{
			$uzivatel = $spravceUzivatelu->vratUzivatele();
			$produkt = $spravcePojisteni->vratJedenProdukt(array($parametry[1]));
			$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));

			if (!$uzivatel['admin'] && ($uzivatel['pojistenec_id'] != $parametry[0] || $uzivatel['pojistenec_id'] != $produkt['pojistenec_id'])) {
					$this->presmeruj('chyba');
				}
			// kontrola, zda jsou ID pojistence a ID pojisteni validni, jinak přesměrujeme na ChybaKontroler
			elseif ($uzivatel['admin'] && (!$pojistenec || !$produkt)) {
				$this->presmeruj('chyba');
			}
		} else {
			$this->presmeruj('chyba');
		}
		
		// nacteni zaznamu daneho pojistence
		$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
		// nacteni zaznamu daneho pojistneho produktu
		$produkt = $spravcePojisteni->vratJedenProdukt(array($parametry[1]));

		// Je odeslán formulář
		if ($_POST) 
		{
			try
			{
				$validator = new Validator();
				$validator->validujPojisteni();
				// nastaveni pole pro intersect
				$pojisteni = array(
					'poj_produkt' => $_POST['poj_produkt'],
					'poj_castka' => $_POST['poj_castka'],
					'predmet_poj' => $_POST['predmet_poj'],
					'platnost_od' => FormatHelper::datumDoDB($_POST['platnost_od']),
					'platnost_do' => FormatHelper::datumDoDB($_POST['platnost_do']),
				);
				// ulozeni produktu do databaze
				$spravcePojisteni->aktualizujPojisteni($pojisteni, array($parametry[1]));
				$this->pridejZpravu(TypZpravy::Uspech, 'Produkt ' . $produkt['poj_produkt'] . ' byl aktualizován.');
				$this->presmeruj("detail-pojistence/{$parametry[0]}");
			}
			catch (ChybaUzivatele $chyba)
			{
				$this->pridejZpravu(TypZpravy::Chyba, $chyba->getMessage());
			}
		}

		//predani promennych do pohledu
		$this->data['pojistenec'] = $pojistenec;
		$this->data['id_pojistence'] = $parametry[0];
		$this->data['id_pojisteni'] = $parametry[1];
		$this->data['produkt'] = $produkt;
		$this->data['uzivatel'] = $spravceUzivatelu->vratUzivatele();
		
		
		$this->pohled = 'editovat-pojisteni';
	}
}