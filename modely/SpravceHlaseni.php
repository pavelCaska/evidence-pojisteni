<?php

/**
 * Třída poskytuje metody pro praci s tabulkou `hlaseni`
 */
class SpravceHlaseni
{
	
    /**
     * funkce vlozi zaznam noveho hlaseni do tabulky `hlaseni`
     * @param array $hlaseni
     * @return bool
     */
	public function vlozHlaseni (array $hlaseni) : bool
	{
		return Db::vloz('hlaseni', $hlaseni);
	}

    /**
     * Vytvoří 1. parametr funkce zmenHlaseni tzn. pole s klíčem zpracováno a hodnotou 1
     * @return array
     */
    public function nastavHodnotyProHlaseni() : array
	{
		return array(
			'zpracovano' => 1
		);
	}

    /**
     * Změní záznam v tabulce `hlaseni` podle ID na základě hodnot předaných v poli $hlaseni
     * @param array $hlaseni
     * @param array $id
     * @return bool
     */
    public function zmenHlaseni (array $hlaseni, array $id) : bool
	{
		return Db::zmen('hlaseni', $hlaseni, 'WHERE hlaseni_id = ?', $id);
	}

    /**
     * Vrati zaznam z tabulky `hlaseni` dle ID jako pole hodnot
     * @param array $id
     * @return array|bool
     */
	public function vratHlaseni (array $id) : array|bool
	{
		return Db::dotazJeden('
		SELECT *
		FROM hlaseni
		WHERE hlaseni_id = ?',
		$id
	);
	}

	// chronologicky prehled vsech udalosti

    /**
     * Vrati chronologicky prehled vsech hlaseni pro admina, razeno dle data nahlaseni, tzn. nejmladsi nezpracovane pred zpracovanymi
     * @return array|bool
     */
	public function vratHistoriiHlaseni() : array|bool
	{
		return Db::dotazVsechny('
		SELECT `pojistenci_id`, `jmeno`, `prijmeni`, `produkt_id`, `poj_produkt`, `hlaseni_id`, `nazev`, `datum_vzniku`, `vyse_skody`, `datum_nahlaseni`, `zpracovano`
		FROM `hlaseni`
		JOIN `pojisteni`
		ON `produkt_id` = `pojisteni_id`
		JOIN `pojistenci`
		ON `pojistenci_id` = `pojistenec_id`
		ORDER BY `zpracovano` ASC, `datum_nahlaseni` DESC
		');
	}

    /**
     * Vrati chronologicky prehled vsech hlaseni pro pojistnika dle ID, razeno dle data nahlaseni, tzn. nejmladsi nezpracovane pred zpracovanymi
     * @param array $id
     * @return array|bool
     */
    public function vratHistoriiHlaseniDleID(array $id) : array|bool
	{
		return Db::dotazVsechny('
		SELECT `pojistenci_id`, `jmeno`, `prijmeni`, `produkt_id`, `poj_produkt`, `hlaseni_id`, `nazev`, `datum_vzniku`, `vyse_skody`, `datum_nahlaseni`, `zpracovano`
		FROM `hlaseni`
		JOIN `pojisteni`
		ON `produkt_id` = `pojisteni_id`
		JOIN `pojistenci`
		ON `pojistenci_id` = `pojistenec_id`
		WHERE `pojistenec_id` = ?
		ORDER BY `zpracovano` ASC, `datum_nahlaseni` DESC',
		$id
		);
	}	
}