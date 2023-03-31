<?php

/*
 * Některé funkce jsem adaptoval či převzal z MVC tutoriálu.
 * Více informací na http://www.itnetwork.cz/licence
 */

class SpravceUzivatelu
{	
	
	/**
	 * Vrátí otisk hesla
	 * @param string $heslo Heslo pro vypočítání otisku
	 * @return string Otisk hesla
	 */
	public function vratOtisk(string $heslo) : string
	{
		return password_hash($heslo, PASSWORD_DEFAULT);
	}

    /**
	 * Registruje nového uživatele do aplikace
     * @param string $jmeno
     * @param string $prijmeni
     * @param string $email
     * @param string $registr_kod Registrační kód generovaný při vložení nového pojistníka
     * @param string $prezdivka Přihlašovací jméno
     * @param string $heslo Přihlašovací heslo
     * @param string $hesloZnovu Opakování hesla pro kontrolu
     * @return void
     * @throws ChybaUzivatele
     */
	public function registruj(string $jmeno, string $prijmeni, string $email, string $registr_kod,
		string $prezdivka, string $heslo, string $hesloZnovu) : void
	{
		$spravcePojistencu = new SpravcePojistencu();
		$pojistenci = $spravcePojistencu->vratVsechnyPojistence();
		$pojistenec_id = '';
		foreach ($pojistenci as $pojistenec) 
		{
			if ($jmeno == $pojistenec['jmeno'] && $prijmeni == $pojistenec['prijmeni'] && $email == $pojistenec['email'] && $registr_kod == $pojistenec['registr_kod']) 
			{
				$pojistenec_id = $pojistenec['pojistenci_id'];
			}
		}
		
		if (!$pojistenec_id)
			throw new ChybaUzivatele('Zadané jméno, příjmení, email nebo registrační kód neodpovídají platnému pojištěnci.');
		if ($heslo != $hesloZnovu)
			throw new ChybaUzivatele('Hesla nesouhlasí.');
		$uzivatel = array(
			'prezdivka' => $prezdivka,
			'heslo' => $this->vratOtisk($heslo),
			'pojistenec_id' => $pojistenec_id
		);
		try
		{
			Db::vloz('uzivatele', $uzivatel);
		}
		catch (PDOException $chyba)
		{
			throw new ChybaUzivatele('Pojištěnec, resp. uživatel s touto přezdívkou je již zaregistrovaný.');
		}
	}

	/**
	 * Přihlásí uživatele do aplikace
	 * @param string $prezdivka Přihlašovací jméno
	 * @param string $heslo Přihlašovací heslo
	 * @return void
	 */
	public function prihlas(string $prezdivka, string $heslo) : void
	{
		$uzivatel = Db::dotazJeden('
			SELECT prezdivka, heslo, admin, pojistenec_id
			FROM uzivatele
			WHERE prezdivka = ?
		', array($prezdivka));
		if (!$uzivatel || !password_verify($heslo, $uzivatel['heslo']))
			throw new ChybaUzivatele('Neexistující přezdívka nebo neplatné heslo.');
		$_SESSION['uzivatel'] = $uzivatel;
	}
	
	/**
	 * Odhlásí uživatele
	 * @return void
	 */
	public function odhlas() : void
	{
		unset($_SESSION['uzivatel']);
		$_SESSION = array();
	}
	
	/**
	 * Vrátí aktuálně přihlášeného uživatele
	 * @return array|null Pole s informacemi o přihlášeném uživateli nebo NULL, pokud není žádný uživatel přihlášen
	 */
	public function vratUzivatele() : array|null
	{
		if (isset($_SESSION['uzivatel']))
			return $_SESSION['uzivatel'];
		return null;
	}

	/**
	 * Smaže uživatele v dusledku smazani pojistence, ktery se stal uzivatelem
     * @param array $id
     * @return bool
     */
	 public function smazUzivatele(array $id) : bool
	{
		return Db::dotaz('
			DELETE 
			FROM uzivatele
			WHERE pojistenec_id = ?', 
			$id);
	}

    /**
     * Vrati true, jestlize existuje uzivatel s danym ID pojistence, tzn. pojistenec se zaregistrovbal jako uzivatel aplikace
     * @param array $id
     * @return bool
     */
	 public function overUzivatele(array $id) : bool
	{
		return Db::dotaz('
			SELECT pojistenec_id 
			FROM uzivatele
			WHERE pojistenec_id = ?', 
			$id);
	}
}
