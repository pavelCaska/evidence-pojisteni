<?php

/**
 * Kontroler pro prehled/rozcestnik pojistnych udalosti pro admina
 */
class PojistenecHlaseniKontroler extends Kontroler
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

		$udalosti = $spravceHlaseni->vratHistoriiHlaseniDleID(array($parametry[0]));

        // pridani hodnoty pro navrat tlacikem zpet
        $_SESSION['previous_location'] = 'hlaseni';

		//predani promennych do pohledu
		$this->data['udalosti'] = $udalosti;
		
		$this->pohled = 'pojistenec-hlaseni';
	}
}