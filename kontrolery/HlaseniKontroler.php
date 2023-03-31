<?php

/**
 * Kontroler pro prehled/rozcestnik pojistnych udalosti pro admina
 */
class HlaseniKontroler extends Kontroler
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

		//logika pro administraci
		$uzivatel = $spravceUzivatelu->vratUzivatele();
		if(!$uzivatel['admin']) {
			$this->presmeruj("pojistenec-hlaseni/{$uzivatel['pojistenec_id']}");
		} else {
			//nacteni vsech zaznamu pojistencu
			$udalosti = $spravceHlaseni->vratHistoriiHlaseni();
		}
		
        // pridani hodnoty pro navrat tlacikem zpet
		$_SESSION['previous_location'] = 'hlaseni';

		//predani promennych do pohledu
		$this->data['udalosti'] = $udalosti;
		$this->data['uzivatel'] = $spravceUzivatelu->vratUzivatele();

		$this->pohled = 'hlaseni';

	}
}