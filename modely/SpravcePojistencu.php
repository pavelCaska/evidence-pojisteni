<?php

/**
 * Třída poskytuje metody pro praci s tabulkou `pojistenci`
 */
class SpravcePojistencu
{
	
    /**
     * Vlozi zaznam noveho pojistence do tabulky `pojistenci`
     * @param array $pojistenec
     * @return bool
     */
    public function vlozNovehoPojistence (array $pojistenec) : bool
	{
		return Db::vloz('pojistenci', $pojistenec);
	}

    /**
     * Zmeni zaznam stavajiciho pojistence podle ID
     * @param array $pojistenec
     * @param array $id
     * @return bool
     */
	public function aktualizujPojistence (array $pojistenec, array $id) : bool
	{
		return Db::zmen('pojistenci', $pojistenec, 'WHERE pojistenci_id = ?', $id);
	}
	
    /**
     * Vymaze pojistence podle ID
     * @param array $id
     * @return bool
     */
	public function smazPojistence(array $id) : bool
	{
		return Db::dotaz('
			DELETE 
			FROM pojistenci
			WHERE pojistenci_id = ?', 
			$id);
	}

    /**
     * Vrati zaznam pojistence dle ID jako pole
     * @param array $id
     * @return array|bool
     */
	public function vratJednohoPojistence (array $id) : array|bool
	{
		return Db::dotazJeden('
			SELECT *
			FROM pojistenci
			WHERE pojistenci_id = ?',
			$id
			);
	}

    /**
     * Vrati pole se zaznamy vsech pojistencu
     * @return array|bool
     */
	public function vratVsechnyPojistence() : array|bool
	{
		return Db::dotazVsechny('
			SELECT *
			FROM pojistenci
			ORDER BY prijmeni
        ');	
	}
}