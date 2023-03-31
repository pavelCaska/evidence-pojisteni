<?php

/**
 * Kontroler pro zpracovani pojistence a prirazenych pojistnych produktu
 */
class DetailPojistenceKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Detail pojištěnce',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná rozšířená verze'
		);
		
		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();
		$spravceUzivatelu = new SpravceUzivatelu();
		
		// nesmi chybet parametr URL, resp. musi to byt cislo
		if (!empty($parametry[0]) && is_numeric($parametry[0])) {
			$uzivatel = $spravceUzivatelu->vratUzivatele();
			$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
			// zadany ciselny parametr neodpovida prihlasenemu pojistenci
			if (!$uzivatel['admin'] && $uzivatel['pojistenec_id'] != $parametry[0]) {
				$this->presmeruj('chyba');
			// pro admina musi ciselny parametr odpovidat existujicimu pojistenci
			} elseif ($uzivatel['admin'] && !$pojistenec){
				$this->presmeruj('chyba');
			}
			// Pokud  parametr neni cislo
		} else {
			$this->presmeruj('chyba');
		}
		
		// nacteni zaznamu daneho pojistence
		$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
		// nacteni zaznamu daneho pojistneho produktu
		$pojisteni = $spravcePojisteni->vratVsechnaPojisteni(array($parametry[0]));

		// Obsluha tlacitek
		if ($_POST) {
			// zpracovani tlacitka smazat pojistence, ktere se nachazi pod tabulkou
			if (isset($_POST['pojistenci_id_smaz'])) {
				//kontrola, zda ma pojistenec prirazen pojistny produkt
				$existujePojisteni = $spravcePojisteni->vratVsechnaPojisteni(array($_POST['pojistenci_id_smaz']));
				
				//ma-li prirazeny pojistny produkt, nelze smazat, generuje se varovani
				if ($existujePojisteni) {
					$this->pridejZpravu(TypZpravy::Chyba, 'Pojištěnec má přirazený nejméně jeden pojistný produkt. Nejprve prosím smažte toto pojištění!');
				} else {
					//nema-li prirazeny produkt, lze pojistence smazat
					$spravcePojistencu->smazPojistence(array($_POST['pojistenci_id_smaz']));
					if ($spravceUzivatelu->overUzivatele(array($_POST['pojistenci_id_smaz']))) {
						//pokud se pojištěnec zaregistroval jako uživatel, je třeba smazat také záznam uživatele
						// restrikce nelze použít, protože tabulka `uzivatele` obsahuje navíc adminy, kteří nemohou být pojištěnci
						$spravceUzivatelu->smazUzivatele(array($_POST['pojistenci_id_smaz']));
						$this->pridejZpravu(TypZpravy::Uspech, 'Pojištěnec ' . $pojistenec['jmeno'] . ' ' . $pojistenec['prijmeni'] . ' byl smazán z evidence.');
						$this->presmeruj('pojistenci');
					} else {
						$this->pridejZpravu(TypZpravy::Uspech, 'Pojištěnec ' . $pojistenec['jmeno'] . ' ' . $pojistenec['prijmeni'] . ' byl smazán z evidence.');
						$this->presmeruj('pojistenci');
					}
				}
			}
			//zpracovani tlacitka edituj v seznamu pojisteni
			elseif (isset($_POST['pojisteni_id_edituj'])) {
				
				$this->presmeruj("editovat-pojisteni/$parametry[0]/{$_POST['pojisteni_id_edituj']}");
			}
			//zpracovani tlacitka smazat v seznamu pojisteni
			elseif (isset($_POST['pojisteni_id_smaz'])) {
				//nacteni zaznamu pro zobrazeni ve zprave
				$produkt = $spravcePojisteni->vratJedenProdukt(array($_POST['pojisteni_id_smaz']));
				//smazani vybraneho pojistneho produktu
				$spravcePojisteni->smazPojisteni(array($_POST['pojisteni_id_smaz']));
				//ulozeni zpravy do session
				$this->pridejZpravu(TypZpravy::Uspech, 'Produkt ' . $produkt['poj_produkt'] . ' byl smazán z evidence.');
				$this->presmeruj("detail-pojistence/{$parametry[0]}");
			}
		}
		
		// pridani hodnoty pro navrat tlacikem zpet
		$_SESSION['previous_location'] = "detail-pojistence/$parametry[0]";
		
		//predani promennych do pohledu
		$this->data['pojistenec'] = $pojistenec;
		$this->data['id_pojistence'] = $parametry[0];
		$this->data['pojisteni'] = $pojisteni;
		$this->data['uzivatel'] = $spravceUzivatelu->vratUzivatele();

		$this->pohled = 'detail-pojistence';
	}
}