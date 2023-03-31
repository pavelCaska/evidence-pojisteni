<?php

/**
 * Kontroler pro prehled/rozcestnik pojistnych udalosti pro admina
 */
class UdalostiKontroler extends Kontroler
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
		$spravceUdalosti = new SpravceUdalosti();

		//logika pro administraci
		$uzivatel = $spravceUzivatelu->vratUzivatele();
		if(!$uzivatel['admin']) {
			$this->presmeruj("pojistenec-udalosti/{$uzivatel['pojistenec_id']}");
		} else {
			//nacteni vsech zaznamu pojistencu
			$udalosti = $spravceUdalosti->vratHistoriiUdalosti();
		}
		
		// nastaveni hodnoty pro navrat tlacitkm zpet
		$_SESSION['previous_location'] = 'udalosti';
		//predani promennych do pohledu
		$this->data['udalosti'] = $udalosti;
		$this->data['uzivatel'] = $spravceUzivatelu->vratUzivatele();
		
		$this->pohled = 'udalosti';
	}
}