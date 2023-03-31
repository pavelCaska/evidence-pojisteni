<?php

/*
 * Odkaz na http://www.itnetwork.cz
 */

/**
 * Router je speciální typ kontroleru, který podle URL adresy zavolá
 * správný kontroler a jím vytvořený pohled vloží do šablony stránky
 */
class SmerovacKontroler extends Kontroler
{
    /**
     * @var Kontroler Instance kontroleru
     */
	protected Kontroler $kontroler;

    /**
     * Metoda převede pomlčkovou variantu controlleru na název třídy
     * @param string $text Řetězec v pomlckove-notaci
     * @return string Řetězec převedený do velbloudiNotace
     */
	private function pomlckyDoVelbloudiNotace(string $text) : string
	{
		$veta = str_replace('-', ' ', $text);
		$veta = ucwords($veta);
		$veta = str_replace(' ', '', $veta);
		return $veta;
	}

    /**
     * Naparsuje URL adresu podle lomítek a vrátí pole parametrů
     * @param string $url URL adresa k naparsování
     * @return array Pole URL parametrů
     */
	private function parsujURL(string $url) : array
	{
		// Naparsuje jednotlivé části URL adresy do asociativního pole
        $naparsovanaURL = parse_url($url);
		// Odstranění počátečního lomítka
		$naparsovanaURL["path"] = ltrim($naparsovanaURL["path"], "/");
		// Odstranění bílých znaků kolem adresy
		$naparsovanaURL["path"] = trim($naparsovanaURL["path"]);
		// Rozbití řetězce podle lomítek
		$rozdelenaCesta = explode("/", $naparsovanaURL["path"]);
		return $rozdelenaCesta;
	}

    /**
     * Naparsování URL adresy a vytvoření příslušného kontroleru
     * @param array $parametry
     * @return void
     */
    public function zpracuj(array $parametry) : void
    {
		// kontroler je 1. parametr URL
		$naparsovanaURL = $this->parsujURL($parametry[0]);
				
		// kdyz v URL neprisel nazev kontroleru jako 1. parametr, presmeruj na prihlaseni
		if (empty($naparsovanaURL[0]))		
			$this->presmeruj('prihlaseni');		

		//vlozeni nazvu tridy kontroleru do promenne
		$tridaKontroleru = $this->pomlckyDoVelbloudiNotace(array_shift($naparsovanaURL)) . 'Kontroler';
		
		//kdyz existuje odpovidajici soubor kontroleru, vytvori jeho instanci, jinak zobrazit stranku chyba
		if (file_exists('kontrolery/' . $tridaKontroleru . '.php'))
			$this->kontroler = new $tridaKontroleru;
		else
			$this->presmeruj('chyba');
		
		// Volání kontroleru
        $this->kontroler->zpracuj($naparsovanaURL);
		
		// Nastavení proměnných pro šablonu
		$spravceUzivatelu = new SpravceUzivatelu();
		$uzivatel = $spravceUzivatelu->vratUzivatele();
		if ($uzivatel)
		{
			$this->data['prezdivka'] = $uzivatel['prezdivka'];
			$this->data['admin'] = $uzivatel['admin'];
		} else {
			$this->data['prezdivka'] = '';
			$this->data['admin'] = '';
		}
		
		$this->data['titulek'] = $this->kontroler->hlavicka['titulek'];
		$this->data['popis'] = $this->kontroler->hlavicka['popis'];
		$this->data['klicova_slova'] = $this->kontroler->hlavicka['klicova_slova'];
		$this->data['zpravy'] = $this->vratZpravy();
		
		// Nastavení hlavní šablony
		$this->pohled = 'rozlozeni';
    }
}