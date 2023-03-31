<?php
class FormatHelper
{
	public $vstupniDatum;
	
	//

    /**
     * Prevede pravopisne spravny i nespravny cesky format datumu na databazovy
     * @param $vstupniDatum
     * @return string|null
     */
    public static function datumDoDB($vstupniDatum)
	{
		if (!$vstupniDatum) {
			return null;
		} else 
		$datum_pole = explode('.', $vstupniDatum);
		$den = trim($datum_pole[0]);
		$mesic = trim($datum_pole[1]);
		$rok = trim($datum_pole[2]);
	
		return $rok . '-' . $mesic . '-' . $den;
	}
	
    /**
     * Prevede databazovy format datumu na cesky format dle zadani, ackoliv 1.1.2020 neni z hlediska ceskeho pravopisu spravne
     * @param $vstupniDatum
     * @return string|null
     */
    public static function datumDoFormulare($vstupniDatum)
	{
		if (!$vstupniDatum) {
			return null;
		} else
		$datum_pole = explode('-', $vstupniDatum);
		$den = ltrim($datum_pole[2], "0");
		$mesic = ltrim($datum_pole[1], "0");
		$rok = $datum_pole[0];
	
		return $den . '.' . $mesic . '.' . $rok;
	}
}
