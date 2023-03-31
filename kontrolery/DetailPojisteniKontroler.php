<?php

/**
 * Kontroler pro zpracovani pojistnych udalosti vcetne pojistnych plneni
 */
class DetailPojisteniKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Detail pojištění',
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
			// kontrola, zda jsou ID pojistence a ID pojisteni validni, jinak přesměrovat na ChybaKontroler
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
		// nacteni zaznamu vsech udalosti daneho pojistneho produktu
		$udalosti = $spravceUdalosti->vratprehledUdalosti(array($parametry[1]));

		// Obsluha tlacitek
		if ($_POST) {
			// zpracovani tlacitka smazat - zaznamy z tabulky plneni se kaskadove smazou
			if (isset($_POST['udalosti_id_smaz'])) {
				$udalost = $spravceUdalosti->vratUdalost(array($_POST['udalosti_id_smaz']));
				$spravceUdalosti->smazUdalost(array($_POST['udalosti_id_smaz']));
				$this->pridejZpravu(TypZpravy::Uspech, 'Událost ' . '"' . $udalost['nazev'] . '"' . ' byla smazána z evidence.');	
				$this->presmeruj("detail-pojisteni/$parametry[0]/$parametry[1]");
			} elseif (isset($_POST['udalosti_id_edituj'])) { //zpracovani tlacitka edituj
				$udalost = $spravceUdalosti->vratUdalost(array($_POST['udalosti_id_edituj']));
				// nastaveni hodnoty pro navrat tlacitkem zpet ze stranky 'detail-pojisteni' 
				$_SESSION['before_previous_location'] = $_SESSION['previous_location'];
				// nastaveni hodnoty pro navrat tlacitkem zpet
				$_SESSION['previous_location'] = "detail-pojisteni/$parametry[0]/$parametry[1]";
				$this->presmeruj("editovat-udalost/$parametry[0]/$parametry[1]/{$_POST['udalosti_id_edituj']}");
			}
		}

		// vlozeni hodnoty pro navrat tlacitkem zpet ze stranky 'detail-pojisteni'
		if (isset($_SESSION['previous_location']) && $_SESSION['previous_location'] == "detail-pojisteni/$parametry[0]/$parametry[1]") {
		$_SESSION['previous_location'] = $_SESSION['before_previous_location'];
		$_SESSION['before_previous_location'] = '';
		}

		//predani promennych do pohledu
		$this->data['pojistenec'] = $pojistenec;
		$this->data['id_pojistence'] = $parametry[0];
		$this->data['produkt'] = $produkt;
		$this->data['udalosti'] = $udalosti;
		$this->data['uzivatel'] = $spravceUzivatelu->vratUzivatele();
		
		$this->pohled = 'detail-pojisteni';
	}
}