<?php

/**
 * Třída poskytuje metody pro praci s tabulkou `udalosti`
 */
class SpravceUdalosti
{

    /**
     * Vlozi novy zaznam pojistne udalosti do tabulky `udalosti`
     * @param array $udalost
     * @return bool
     */
    public function vlozUdalost (array $udalost) : bool
	{
		return Db::vloz('udalosti', $udalost);
	}

    /**
     * Vlozi novy zaznam plneni prirazeneho pojistne udalosti do tabulky `plneni`
     * @param array $udalost
     * @return bool
     */
    public function vlozPlneni (array $udalost) : bool
	{
		return Db::vloz('plneni', $udalost);
	}

    /**
     * Vrati pole hodnot pro pouziti jako parametr funkce vlozUdalost/zmenUdalost
     * @return array
     */
    public function nastavHodnotyProUdalosti() : array
	{
		return array(
			'nazev' => $_POST['nazev'],
			'datum_vzniku' => FormatHelper::datumDoDB($_POST['datum_vzniku']),
			'datum_nahlaseni' => FormatHelper::datumDoDB($_POST['datum_nahlaseni']),
			'vyse_skody' => $_POST['vyse_skody'],
			'produkt_id' => $_POST['produkt_id'],
		);
	}
	
    /**
     * Zmeni zaznam udalosti v tabulce dle ID
     * @param array $udalost
     * @param array $id
     * @return bool
     */
	public function zmenUdalost (array $udalost, array $id) : bool
	{
		return Db::zmen('udalosti', $udalost, 'WHERE udalosti_id = ?', $id);
	}

    /**
     * Vrati pole hodnot pro pouziti jako parametr funkce vlozPlneni/zmenPlneni
     * @param $udalost_id
     * @return array
     */
    public function nastavHodnotyProPlneni($udalost_id) : array
	{
		return array(
			'spoluucast' => $_POST['spoluucast'],
			'vyse_plneni' => $_POST['vyse_plneni'],
			'datum_schvaleni' => FormatHelper::datumDoDB($_POST['datum_schvaleni']),
			'datum_vyplaty' => FormatHelper::datumDoDB($_POST['datum_vyplaty']),
			'udalost_id' => $udalost_id
		);
	}

    /**
     * Zmeni zaznam plneni v tabulce `plneni` dle ID udalosti
     * @param array $plneni
     * @param array $id
     * @return bool
     */
    public function zmenPlneni (array $plneni, array $id) : bool
	{
		return Db::zmen('plneni', $plneni, 'WHERE udalost_id = ?', $id);
	}
	
    /**
     * Vymaze udalost podle ID
     * @param array $id
     * @return bool
     */
	public function smazUdalost(array $id) : bool
	{
		return Db::dotaz('
			DELETE 
			FROM udalosti
			WHERE udalosti_id = ?', 
	 		$id);
	}

    /**
     * Vrati pole se zaznamy vsech pojistnych udalosti dle ID pojistneho produktu
     * @param array $produkt_id
     * @return array|bool
     */
	public function vratprehledUdalosti(array $produkt_id) : array|bool
	{
		return Db::dotazVsechny('
		SELECT nazev, datum_vzniku, vyse_skody, vyse_plneni, spoluucast, datum_vyplaty, udalosti_id
		FROM udalosti 
		JOIN plneni
		ON udalosti_id = udalost_id
		WHERE produkt_id = ?
		ORDER BY udalosti_id',
		$produkt_id);
	}
	
    /**
     * Vrati dle ID udalosti zaznam z tabulky `udalosti` a jemu prirazeny zaznam z tabulky `plneni`
     * @param array $udalosti_id
     * @return array|bool
     */
	public function vratUdalostPlneni(array $udalosti_id) : array|bool
	{
		return Db::dotazJeden('
		SELECT nazev, vyse_skody, datum_vzniku, datum_nahlaseni, spoluucast, vyse_plneni, datum_schvaleni, datum_vyplaty, udalosti_id
		FROM udalosti 
		JOIN plneni
		ON udalosti_id = udalost_id
		WHERE udalosti_id = ?',
		$udalosti_id);
	}
    /**
     * Vrati dle ID udalosti pole s jednim zaznamem z tabulky `udalosti`
     * @param array $udalosti_id
     * @return array|bool
     */
	public function vratUdalost(array $udalosti_id) : array|bool
	{
	 	return Db::dotazJeden('
		SELECT *
		FROM udalosti 
		WHERE udalosti_id = ?',
		$udalosti_id);
	}

    /**
     * Vrati pole hodnot pro chronologicky prehled vsech udalosti
     * @return array|bool
     */
	public function vratHistoriiUdalosti() : array|bool
	{
		return Db::dotazVsechny('
		SELECT `pojistenci_id`, `jmeno`, `prijmeni`, `produkt_id`, `poj_produkt`, `udalosti_id`, `nazev`, `datum_vzniku`, `vyse_skody`, `datum_nahlaseni`, `spoluucast`, `vyse_plneni`, `datum_schvaleni`, `datum_vyplaty`
		FROM `udalosti`
		JOIN `plneni`
		ON `udalosti_id` = `udalost_id`
		JOIN `pojisteni`
		ON `produkt_id` = `pojisteni_id`
		JOIN `pojistenci`
		ON `pojistenci_id` = `pojistenec_id`
		ORDER BY `datum_nahlaseni` DESC
		');
	}

    /**
     * Vrati pole hodnot pro chronologicky prehled udalosti prirazenych jednomu pojistenci dle ID pojistence
     * @param array $id
     * @return array|bool
     */
    public function vratHistoriiUdalostiDleID(array $id) : array|bool
	{
		return Db::dotazVsechny('
		SELECT `produkt_id`, `poj_produkt`, `udalosti_id`, `nazev`, `datum_vzniku`, `vyse_skody`, `datum_nahlaseni`, `spoluucast`, `vyse_plneni`, `datum_schvaleni`, `datum_vyplaty`
		FROM `udalosti`
		JOIN `plneni`
		ON `udalosti_id` = `udalost_id`
		JOIN `pojisteni`
		ON `produkt_id` = `pojisteni_id`
		JOIN `pojistenci`
		ON `pojistenci_id` = `pojistenec_id`
		WHERE `pojistenec_id` = ?
		ORDER BY `datum_nahlaseni` DESC',
		$id
		);
	}	
}