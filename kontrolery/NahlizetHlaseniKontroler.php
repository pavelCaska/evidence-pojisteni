<?php

/**
 * Kontroler pro prehled/rozcestnik pojistnych udalosti pro admina
 */
class NahlizetHlaseniKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Seznam pojistných událostí',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná rozšířená verze'
		);
		
		// Vytvoření instance modelu
		$spravceUzivatelu = new SpravceUzivatelu();
		$spravceHlaseni = new SpravceHlaseni();
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();

		// nesmi chybet prvni, druhy a treti parametr URL, resp. musi to byt cislo
		if (!empty($parametry[0]) && is_numeric($parametry[0]) || !empty($parametry[1]) && is_numeric($parametry[1]) || !empty($parametry[2]) && is_numeric($parametry[2]))
		{
			$uzivatel = $spravceUzivatelu->vratUzivatele();
			$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
			$produkt = $spravcePojisteni->vratJedenProdukt(array($parametry[1]));
			$udalost = $spravceHlaseni->vratHlaseni(array($parametry[2]));

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

		// nacteni zaznamu hlaseni
		$udalost = $spravceHlaseni->vratHlaseni(array($parametry[2]));
		// nastaveni hodnoty pro navrat tlacitkem zpet
		$_SESSION['previous_location'] = 'hlaseni';

		//predani promennych do pohledu
		$this->data['udalost'] = $udalost;
		$this->data['uzivatel'] = $spravceUzivatelu->vratUzivatele();
		$this->data['produkt'] = $spravcePojisteni->vratJedenProdukt(array($parametry[1]));
		$this->data['pojistenec'] = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
		
		$this->pohled = 'nahlizet-hlaseni';
	}
}