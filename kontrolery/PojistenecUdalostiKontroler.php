<?php

/**
 * Kontroler pro prehled pojistnych udalosti konkretniho pojistence
 */
class PojistenecUdalostiKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Seznam pojistných událostí zobrazovany pojistenci',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná rozšířená verze'
		);
		
		// Vytvoření instance modelu
		$spravceUzivatelu = new SpravceUzivatelu();
		$spravceUdalosti = new SpravceUdalosti();
		
		// nesmi chybet parametr URL, resp. musi to byt cislo
		if (!empty($parametry[0]) && is_numeric($parametry[0])) {
		$uzivatel = $spravceUzivatelu->vratUzivatele();
		// zadany ciselny parametr neodpovida prihlasenemu pojistenci
			if (!$uzivatel['admin'] && $uzivatel['pojistenec_id'] != $parametry[0]) {
			$this->presmeruj('chyba');
			}
			// Pokud  parametr neni cislo
		} else {
			$this->presmeruj('chyba');
		}
		
		// nacteni zaznamu vsech udalosti daneho pojistneho pojistence
		$udalosti = $spravceUdalosti->vratHistoriiUdalostiDleID(array($parametry[0]));
		
		// nastaveni hodnoty pro navrat tlacitkem zpet
		$_SESSION['previous_location'] = "pojistenec-udalosti/$parametry[0]";

		//predani promennych do pohledu
		$this->data['pojistenec'] = $parametry[0];
		$this->data['udalosti'] = $udalosti;
		$this->data['uzivatel'] = $spravceUzivatelu->vratUzivatele();
		
		$this->pohled = 'pojistenec-udalosti';
	}
}