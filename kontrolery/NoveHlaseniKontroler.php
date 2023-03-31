<?php

/**
 * Kontroler pro vytvoreni zaznamu noveho pojistneho produktu
 */
class NoveHlaseniKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Hlášení škodní události',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná verze - minimální požadavky ke splnění'
		);
		
		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();
		$spravceHlaseni = new SpravceHlaseni();
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
		$produkt = $spravcePojisteni->vratJedenProdukt(array($parametry[1]));
		// nastaveni dnesniho data
		$dnes = new DateTime();

		// Je odeslán formulář
		if ($_POST) 
		{
			try
			{
				$validator = new Validator();
				$validator->validujHlaseni();
				//nastaveni pole pro intersect
				$hlaseni = array(
					'nazev' => $_POST['nazev'],
					'vyse_skody' => $_POST['vyse_skody'],
					'datum_vzniku' => FormatHelper::datumDoDB($_POST['datum_vzniku']),
					'datum_nahlaseni' => FormatHelper::datumDoDB($_POST['datum_nahlaseni']),
					'produkt_id' => $_POST['produkt_id'],
				);
				// vlozeni noveho zaznamu do databaze
				$spravceHlaseni->vlozHlaseni($hlaseni);
				// ulozeni zpravy do session
				$this->pridejZpravu(TypZpravy::Uspech, 'Škodní událost ' . '"' . $_POST['nazev'] . '"' . ' byla nahlášena.');
				$this->presmeruj("detail-pojisteni/{$parametry[0]}/{$parametry[1]}");
			}
			catch (ChybaUzivatele $chyba)
			{
				$this->pridejZpravu(TypZpravy::Chyba, $chyba->getMessage());
			}			
		}

		//predani promennych do pohledu
		$this->data['produkt'] = $produkt;
		$this->data['datum_nahlaseni'] = $dnes->format('j.n.Y');
		
		$this->pohled = 'nove-hlaseni';
	}
}