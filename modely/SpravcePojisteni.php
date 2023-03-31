<?php

/**
 * Třída poskytuje metody pro praci s tabulkou `pojisteni`
 */
class SpravcePojisteni
{
	
    /**
     * Vlozi zaznam noveho pojistneho produktu do tabulky `tabulky`
     * @param array $pojisteni
     * @return bool
     */
    public function vlozNovePojisteni (array $pojisteni) : bool
	{
		return Db::vloz('pojisteni', $pojisteni);
	}

    /**
     * Zmeni zaznam daneho pojistneho produktu dle ID
     * @param array $pojisteni
     * @param array $id
     * @return bool
     */
	public function aktualizujPojisteni (array $pojisteni, array $id) : bool
	{
		return Db::zmen('pojisteni', $pojisteni, 'WHERE pojisteni_id = ?', $id);
	}
	
    /**
     * Vymaze zaznam daneho pojistneho produktu dle jeho ID plus kaskadovite vymaze prirazene udalosti a plneni
     * @param array $id
     * @return bool
     */
	public function smazPojisteni(array $id) : bool
	{
		return Db::dotaz('
			DELETE 
			FROM pojisteni
			WHERE pojisteni_id = ?', 
			$id);
	}
	
    /**
     * Vrati pole zaznamu vsech pojistnych produktu prirazenych danemu pojistenci dle jeho ID
     * @param array $id
     * @return array|bool
     */
	public function vratVsechnaPojisteni(array $id) : array|bool
	{
		return Db::dotazVsechny('
			SELECT *
			FROM pojisteni
			WHERE pojistenec_id = ?
			ORDER BY poj_produkt',
			$id);	
	}
	
    /**
     * Vrati pole se zaznamem pojistneho produktu dle jeho ID
     * @param array $id
     * @return array|bool
     */
	public function vratJedenProdukt(array $id) : array|bool
	{
		return Db::dotazJeden('
			SELECT *
			FROM pojisteni
			WHERE pojisteni_id = ?',
			$id);	
	}
}