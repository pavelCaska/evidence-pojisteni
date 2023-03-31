<?php

/**
 * Kontroler pro zpracování stránky O aplikaci
 */
class OAplikaciKontroler extends Kontroler
{
    public function zpracuj(array $parametry) : void
    {
		$this->hlavicka['titulek'] = 'O aplikaci';
		// Nastavení šablony
		$this->pohled = 'o-aplikaci';
    }
}