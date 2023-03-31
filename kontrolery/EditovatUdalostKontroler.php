<?php

/**
 * Kontroler pro vytvoreni zaznamu noveho pojistneho produktu
 */
class EditovatUdalostKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Editovat pojistnou událost',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná rozšířená verze'
		);
		
		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();
		$spravceUzivatelu = new SpravceUzivatelu();
		$spravceUdalosti = new SpravceUdalosti();
		
		// nesmi chybet prvni, druhy a treti parametr URL, resp. musi to byt cislo
		if (!empty($parametry[0]) && is_numeric($parametry[0]) || !empty($parametry[1]) && is_numeric($parametry[1]) || !empty($parametry[2]) && is_numeric($parametry[2]))
		{
			$uzivatel = $spravceUzivatelu->vratUzivatele();
			$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
			$produkt = $spravcePojisteni->vratJedenProdukt(array($parametry[1]));
			$udalost = $spravceUdalosti->vratUdalost(array($parametry[2]));

			if (!$uzivatel['admin'] && ($uzivatel['pojistenec_id'] != $parametry[0] || $uzivatel['pojistenec_id'] != $produkt['pojistenec_id']) || $produkt['pojisteni_id'] != $udalost['produkt_id']) {
					$this->presmeruj('chyba');
				}
			// kontrola, zda jsou ID pojistence a ID pojisteni validni, jinak přesměrovat na ChybaKontroler
			elseif ($uzivatel['admin'] && (!$pojistenec || !$produkt || !$udalost)) {
				$this->presmeruj('chyba');
			}
		} else {
			$this->presmeruj('chyba');
		}
				
		// nacteni zaznamu daneho pojistence
		$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
		$produkt = $spravcePojisteni->vratJedenProdukt(array($parametry[1]));
		$udalost = $spravceUdalosti->vratUdalostPlneni(array($parametry[2]));
		
		// Je odeslán formulář
		if ($_POST) 
		{
			$validator = new Validator();
			
			if (empty($_POST['spoluucast']) && empty($_POST['vyse_plneni']) && empty($_POST['datum_schvaleni']) && empty($_POST['datum_vyplaty'])) {
				try 
				{
					$validator->validujUdalost();
					$udalost = $spravceUdalosti->nastavHodnotyProUdalosti();
					$spravceUdalosti->zmenUdalost($udalost, array($parametry[2]));
					$plneni = $spravceUdalosti->nastavHodnotyProPlneni($parametry[2]);
					$spravceUdalosti->zmenPlneni($plneni, array($parametry[2]));
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
					$spravceUdalosti->zmenUdalost($udalost, array($parametry[2]));
					$plneni = $spravceUdalosti->nastavHodnotyProPlneni($parametry[2]);
					$spravceUdalosti->zmenPlneni($plneni, array($parametry[2]));
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

			} elseif ((!empty($_POST['spoluucast']) && !empty($_POST['vyse_plneni'])) && ((empty($_POST['datum_schvaleni']) && empty($_POST['datum_vyplaty'])) || (empty($_POST['datum_schvaleni']) && !empty($_POST['datum_vyplaty'])))) {
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
					$spravceUdalosti->zmenUdalost($udalost, array($parametry[2]));
					$plneni = $spravceUdalosti->nastavHodnotyProPlneni($parametry[2]);
					$spravceUdalosti->zmenPlneni($plneni, array($parametry[2]));
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
					$spravceUdalosti->zmenUdalost($udalost, array($parametry[2]));
					$plneni = $spravceUdalosti->nastavHodnotyProPlneni($parametry[2]);
					$spravceUdalosti->zmenPlneni($plneni, array($parametry[2]));
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
		$this->data['udalost'] = $udalost;
		$this->data['uzivatel'] = $spravceUzivatelu->vratUzivatele();
		
		$this->pohled = 'editovat-udalost';
	}
}