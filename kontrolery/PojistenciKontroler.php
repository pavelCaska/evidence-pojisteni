<?php

/**
 * Kontroler pro zpracovani pojistencu
 */
class PojistenciKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Seznam pojištěnců',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná rozšířená verze'
		);

		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();
		$spravceUzivatelu = new SpravceUzivatelu();
		
		
		//logika pro administraci
		$uzivatel = $spravceUzivatelu->vratUzivatele();
		if(!$uzivatel['admin']) {
			$this->presmeruj("detail-pojistence/{$uzivatel['pojistenec_id']}");
		} else {
			//nacteni vsech zaznamu pojistencu
			$pojistenci = $spravcePojistencu->vratVsechnyPojistence();
		}


		// Obsluha tlacitek
		if ($_POST) {
			// tlacitko smazat (pojistence)
			if (isset($_POST['pojistenci_id_smaz'])) {
				//kontrola, zda ma pojistenec prirazen pojistny produkt
				$existujePojisteni = $spravcePojisteni->vratVsechnaPojisteni(array($_POST['pojistenci_id_smaz']));

				//ma-li prirazeny pojistny produkt, nelze smazat, generuje se varovani
				if ($existujePojisteni) {
					//nacteni hodnot pro varovani
					$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($_POST['pojistenci_id_smaz']));
					$this->pridejZpravu(TypZpravy::Chyba, 'Pojištěnec ' . $pojistenec['jmeno'] . ' ' . $pojistenec['prijmeni'] . ' má přirazený nejméně jeden pojistný produkt. Nejprve prosím smažte toto pojištění!');
				} else {
					//nacteni zaznamu pro zobrazeni ve zprave
					$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($_POST['pojistenci_id_smaz']));
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
			elseif ($_POST['pojistenci_id_edituj']) {
				// obsluha tlacitka editovat
				$this->presmeruj("stavajici-pojistenec/{$_POST['pojistenci_id_edituj']}");
			}
		}
		// nastaveni hodnoty pro navrat tlacitkem zpet		
		$_SESSION['previous_location'] = 'pojistenci';

		//predani promennych do pohledu
		$this->data['pojistenci'] = $pojistenci;
		
		//vypsat pohled
		$this->pohled = 'pojistenci';
	}
}