<?php

/**
 * Kontroler pro vytvoreni zaznamu noveho pojistneho produktu
 */
class NovaUdalostKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Nová pojistná událost',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná rozšířená verze'
		);
		
		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();
		$spravceUzivatelu = new SpravceUzivatelu();
		$spravceUdalosti = new SpravceUdalosti();
		
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
		$produkt = $spravcePojisteni->vratJedenProdukt(array($parametry[1]));

		// Je odeslán formulář
		if ($_POST) 
		{
			$validator = new Validator();

			// validace dalsich inputu, ktere mohou byt prazdne, resp. mohou byt zadavany s casovym odstupem dle prubehu likvidace
			if (empty($_POST['spoluucast']) && empty($_POST['vyse_plneni']) && empty($_POST['datum_schvaleni']) && empty($_POST['datum_vyplaty'])) 
			{
				try
				{
					$validator->validujUdalost();
					//nastaveni pole pro intersect
					$udalost = $spravceUdalosti->nastavHodnotyProUdalosti();
					// vlozeni noveho zaznamu do databaze
					$spravceUdalosti->vlozUdalost($udalost);
					$udalost_id = Db::posledniId();
					$plneni = $spravceUdalosti->nastavHodnotyProPlneni($udalost_id);
					$spravceUdalosti->vlozPlneni($plneni);
					// ulozeni zpravy do session
					$this->pridejZpravu(TypZpravy::Uspech, 'Pojistná událost ' . '"' . $_POST['nazev'] . '"' . ' byla uložena do evidence.');
					$this->presmeruj("detail-pojisteni/{$parametry[0]}/{$parametry[1]}");
				}
				catch (ChybaUzivatele $chyba)
				{
					$this->pridejZpravu(TypZpravy::Chyba, $chyba->getMessage());
				}

			} elseif (empty($_POST['spoluucast']) && (!empty($_POST['vyse_plneni']) || !empty($_POST['datum_schvaleni']) || !empty($_POST['datum_vyplaty']))) 
			{
				$this->pridejZpravu(TypZpravy::Chyba, 'Spoluúčast musí být vyplněna!');

			} elseif (!empty($_POST['spoluucast']) && empty($_POST['vyse_plneni']) && empty($_POST['datum_schvaleni']) && empty($_POST['datum_vyplaty'])) 
			{
				try
				{
					$validator->validujUdalost();
					$validator->validujPlneniSpoluucast();

					$udalost = $spravceUdalosti->nastavHodnotyProUdalosti();
					$spravceUdalosti->vlozUdalost($udalost);
					$udalost_id = Db::posledniId();
					$plneni = $spravceUdalosti->nastavHodnotyProPlneni($udalost_id);
					$spravceUdalosti->vlozPlneni($plneni);
					$this->pridejZpravu(TypZpravy::Uspech, 'Pojistná událost ' . '"' . $_POST['nazev'] . '"' . ' byla uložena do evidence.');
					$this->presmeruj("detail-pojisteni/{$parametry[0]}/{$parametry[1]}");
				}
				catch (ChybaUzivatele $chyba)
				{
					$this->pridejZpravu(TypZpravy::Chyba, $chyba->getMessage());
				}
			
			} elseif ((!empty($_POST['spoluucast']) && empty($_POST['vyse_plneni'])) && 
			((empty($_POST['datum_schvaleni']) && !empty($_POST['datum_vyplaty'])) || 
			(!empty($_POST['datum_schvaleni']) && empty($_POST['datum_vyplaty'])) || 
			(!empty($_POST['datum_schvaleni']) && !empty($_POST['datum_vyplaty'])))) 
			{
				$this->pridejZpravu(TypZpravy::Chyba, 'Výše plnění musí být vyplněna!');

			} elseif ((!empty($_POST['spoluucast']) && !empty($_POST['vyse_plneni'])) && ((empty($_POST['datum_schvaleni']) && empty($_POST['datum_vyplaty'])) || (empty($_POST['datum_schvaleni']) && !empty($_POST['datum_vyplaty'])))) 
			{
				$this->pridejZpravu(TypZpravy::Chyba, 'Datum schválení musí být vyplněno!');

			} elseif (!empty($_POST['spoluucast']) && !empty($_POST['vyse_plneni']) && !empty($_POST['datum_schvaleni']) && empty($_POST['datum_vyplaty'])) 
			{
				try
				{
					$validator->validujUdalost();
					$validator->validujPlneniSpoluucast();
					$validator->validujPlneniVysePlneni();
					$validator->validujPlneniDatumSchvaleni();

					$udalost = $spravceUdalosti->nastavHodnotyProUdalosti();
					$spravceUdalosti->vlozUdalost($udalost);
					$udalost_id = Db::posledniId();
					$plneni = $spravceUdalosti->nastavHodnotyProPlneni($udalost_id);
					$spravceUdalosti->vlozPlneni($plneni);
					$this->pridejZpravu(TypZpravy::Uspech, 'Pojistná událost ' . '"' . $_POST['nazev'] . '"' . ' byla uložena do evidence.');
					$this->presmeruj("detail-pojisteni/{$parametry[0]}/{$parametry[1]}");
				}
				catch (ChybaUzivatele $chyba)
				{
					$this->pridejZpravu(TypZpravy::Chyba, $chyba->getMessage());
				}
			} elseif (!empty($_POST['spoluucast']) && !empty($_POST['vyse_plneni']) && !empty($_POST['datum_schvaleni']) && !empty($_POST['datum_vyplaty'])) 
			{
				try
				{
					$validator->validujUdalost();
					$validator->validujPlneniSpoluucast();
					$validator->validujPlneniVysePlneni();
					$validator->validujPlneniDatumSchvaleni();
					$validator->validujPlneniDatumVyplaty();

					$udalost = $spravceUdalosti->nastavHodnotyProUdalosti();
					$spravceUdalosti->vlozUdalost($udalost);
					$udalost_id = Db::posledniId();
					$plneni = $spravceUdalosti->nastavHodnotyProPlneni($udalost_id);
					$spravceUdalosti->vlozPlneni($plneni);
					$this->pridejZpravu(TypZpravy::Uspech, 'Pojistná událost ' . '"' . $_POST['nazev'] . '"' . ' byla uložena do evidence.');
					$this->presmeruj("detail-pojisteni/{$parametry[0]}/{$parametry[1]}");

				}
				catch (ChybaUzivatele $chyba)
				{
					$this->pridejZpravu(TypZpravy::Chyba, $chyba->getMessage());
				}
			}
		}

		//predani promennych do pohledu
		$this->data['id_pojistence'] = $parametry[0];
		$this->data['id_produktu'] = $parametry[1];
		$this->data['pojistenec'] = $pojistenec;
		$this->data['produkt'] = $produkt;
		$this->data['uzivatel'] = $spravceUzivatelu->vratUzivatele();

		$this->pohled = 'nova-udalost';
	}
}