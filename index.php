<?php
/*
* odkaz na http://www.itnetwork.cz
*/
session_start();

// Nastavení interního kódování pro funkce pro práci s řetězci
mb_internal_encoding("UTF-8");

/**
 * Callback pro automatické načítání tříd controllerů a modelů
 * @param string $trida Název třídy k načtení
 * @return void
 */
function autoloadFunkce(string $trida) : void
{
	// Končí název třídy řetězcem "Kontroler" ?
    if (preg_match('/Kontroler$/', $trida))	
		require("kontrolery/" . $trida . ".php");
	else
		require("modely/" . $trida . ".php");
}

// Registrace callbacku (Pod starým PHP 5.2 je nutné nahradit fcí __autoload())
spl_autoload_register("autoloadFunkce");

// Připojení k databázi POJISTENI
Db::pripoj("uvdb54.active24.cz", "pavelcaska", "TwmCGTvt", "pavelcaska");

// Vytvoření routeru a zpracování parametrů od uživatele z URL
$smerovac = new SmerovacKontroler();
$smerovac->zpracuj(array($_SERVER['REQUEST_URI']));

// Vyrenderování šablony
$smerovac->vypisPohled();
