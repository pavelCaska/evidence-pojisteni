<?php

class Validator 
{
	public function validujPojistence() : void
	{
		if ($_POST['jmeno'] == '')
			throw new ChybaUzivatele('Jméno není vyplněno!');
		if (!preg_match('/^[\p{L}-]+$/u', $_POST['jmeno']))
			throw new ChybaUzivatele('Jméno nemá správný formát!');
		if ($_POST['prijmeni'] == '')
			throw new ChybaUzivatele('Příjmení není vyplněno!');
		if (!preg_match('/^[\p{L}-]+$/u', $_POST['prijmeni']))
			throw new ChybaUzivatele('Příjmení nemá správný formát!');
		if ($_POST['email'] == '')
			throw new ChybaUzivatele('Mailová adresa není vyplněna!');
		if (!preg_match('/\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6}/', $_POST['email']))
			throw new ChybaUzivatele('Mailová adresa nemá správný formát!');
		if ($_POST['telefon'] == '')
			throw new ChybaUzivatele('Číslo telefonu není vyplněno!');
		if (!preg_match('/^(\d{3}\s){2}\d{3}|\d{3}\s+$/', $_POST['telefon']))
			throw new ChybaUzivatele('Číslo telefonu nemá správný formát!');
		if ($_POST['ulice_cp'] == '')
			throw new ChybaUzivatele('Ulice a číslo nejsou vyplněny!');
		if (!preg_match('/^[\p{L}\s|\.\s]+[0-9\/0-9|\w]+$/u', $_POST['ulice_cp']))
			throw new ChybaUzivatele('Ulice a číslo nemají správný formát!');
		if ($_POST['mesto'] == '')
			throw new ChybaUzivatele('Město není vyplněno!');
		if (!preg_match('/^[\p{L}\s|\.\s]+[0-9]|[\p{L}]+$/u', $_POST['mesto']))
			throw new ChybaUzivatele('Město nemá správný formát!');
		if ($_POST['psc'] == '')
			throw new ChybaUzivatele('PSČ není vyplněno!');
		if (!preg_match('/^\d{3}\s\d{2}$/', $_POST['psc']))
			throw new ChybaUzivatele('PSČ nemá správný formát!');
	}

	public function validujPojisteni() : void
	{
		$platnost_od = DateTime::createFromFormat('j.n.Y', $_POST['platnost_od']);
		$platnost_do = DateTime::createFromFormat('j.n.Y', $_POST['platnost_do']);

		if ($_POST['poj_produkt'] == '')
			throw new ChybaUzivatele(('Není vybrán pojišťovací produkt!'));
		if ($_POST['poj_castka'] == '')
			throw new ChybaUzivatele(('Pojistná částka není vyplněna!'));
		if (!filter_var($_POST['poj_castka'], FILTER_VALIDATE_INT))
			throw new ChybaUzivatele(('Pojistná částka není celé číslo!'));
		if ($_POST['poj_castka'] < 0)
			throw new ChybaUzivatele(('Pojistná částka je záporná!'));
		if ($_POST['predmet_poj'] == '')
			throw new ChybaUzivatele(('Předmět pojištění není vyplněn!'));
		if (!preg_match('/^[\p{L}\s|\.\s]+[0-9]|[\p{L}]+$/u', $_POST['predmet_poj']))
			throw new ChybaUzivatele(('Předmět pojištění nemá správný formát!'));
		if ($_POST['platnost_od'] == '')
			throw new ChybaUzivatele(('Datum počátku platnosti není vyplněno!'));
		if (!preg_match('/(^\d{1,2}\.\d{1,2}\.\d{4}$)|(^\d{1,2}\.\s\d{1,2}\.\s\d{4}$)/', $_POST['platnost_od']))
			throw new ChybaUzivatele(('Datum počátku platnosti nemá správný formát!'));
		if ($_POST['platnost_do'] == '')
			throw new ChybaUzivatele(('Datum konce platnosti není vyplněno!'));
		if (!preg_match('/(^\d{1,2}\.\d{1,2}\.\d{4}$)|(^\d{1,2}\.\s\d{1,2}\.\s\d{4}$)/', $_POST['platnost_do']))
			throw new ChybaUzivatele(('Datum konce platnosti nemá správný formát!'));
		if ($platnost_do < $platnost_od)
			throw new ChybaUzivatele(('Datum konce platnosti předchází datum počátku platnosti!'));
	}

	public function validujHlaseni() : void
	{
		$dnes = new DateTime();
		$datum_vzniku = DateTime::createFromFormat('j.n.Y', $_POST['datum_vzniku']);

		if ($_POST['nazev'] == '')
			throw new ChybaUzivatele('Označení škodní události není vyplněno!');
		if (!preg_match('/^[\p{L}\s|\.\s]+[0-9]|[\p{L}]+$/u', $_POST['nazev']))
			throw new ChybaUzivatele('Označení škodní události nemá správný formát!');
		if ($_POST['vyse_skody'] == '')
			throw new ChybaUzivatele('Výše škody není vyplněna!');
		if (!filter_var($_POST['vyse_skody'], FILTER_VALIDATE_INT))
			throw new ChybaUzivatele('Částka škody není celé číslo!');
		if ($_POST['vyse_skody'] < 0)
			throw new ChybaUzivatele('Částka škody je záporná!');
		if ($_POST['datum_vzniku'] == '')
			throw new ChybaUzivatele('Datum vzniku škodní události není vyplněno!');
		if (!preg_match('/(^\d{1,2}\.\d{1,2}\.\d{4}$)|(^\d{1,2}\.\s\d{1,2}\.\s\d{4}$)/', $_POST['datum_vzniku']))
			throw new ChybaUzivatele('Datum vzniku škodní události nemá správný formát!');
		if ($datum_vzniku > $dnes)
			throw new ChybaUzivatele('Datum vzniku škodní události je v budoucnosti!');
	}

	public function validujUdalost() : void
	{
		$dnes = new DateTime();
		$datum_vzniku = DateTime::createFromFormat('j.n.Y', $_POST['datum_vzniku']);
		$datum_nahlaseni = DateTime::createFromFormat('j.n.Y', $_POST['datum_nahlaseni']);

		if ($_POST['nazev'] == '')
			throw new ChybaUzivatele('Označení pojistné události není vyplněno!');
		if (!preg_match('/^[\p{L}\s|\.\s]+[0-9]|[\p{L}]+$/u', $_POST['nazev']))
			throw new ChybaUzivatele('Označení pojistné události nemá správný formát!');
		if ($_POST['vyse_skody'] == '')
			throw new ChybaUzivatele('Výše škody není vyplněna!');
		if (!filter_var($_POST['vyse_skody'], FILTER_VALIDATE_INT))
			throw new ChybaUzivatele('Částka škody není celé číslo!');
		if ($_POST['vyse_skody'] < 0)
			throw new ChybaUzivatele('Částka škody je záporná!');
		if ($_POST['datum_vzniku'] == '')
			throw new ChybaUzivatele('Datum vzniku pojistné události není vyplněno!');
		if (!preg_match('/(^\d{1,2}\.\d{1,2}\.\d{4}$)|(^\d{1,2}\.\s\d{1,2}\.\s\d{4}$)/', $_POST['datum_vzniku']))
			throw new ChybaUzivatele('Datum vzniku pojistné události nemá správný formát!');
		if ($datum_vzniku > $dnes)
			throw new ChybaUzivatele('Datum vzniku pojistné události je v budoucnosti!');
		if ($_POST['datum_nahlaseni'] == '')
			throw new ChybaUzivatele('Datum nahlášení pojistné události není vyplněno!');
		if (!preg_match('/(^\d{1,2}\.\d{1,2}\.\d{4}$)|(^\d{1,2}\.\s\d{1,2}\.\s\d{4}$)/', $_POST['datum_nahlaseni']))
			throw new ChybaUzivatele('Datum nahlášení pojistné události nemá správný formát!');
		if ($datum_nahlaseni > $dnes)
			throw new ChybaUzivatele('Datum nahlášení pojistné události je v budoucnosti!');
		if ($datum_vzniku > $datum_nahlaseni)
			throw new ChybaUzivatele('Datum nahlášení předchází datu vzniku pojistné události!');
	}

	public function validujPlneniSpoluucast() :void
	{
		if ($_POST['spoluucast'] == '')
			throw new ChybaUzivatele('Spoluúčast není vyplněna!');
		if (!filter_var($_POST['spoluucast'], FILTER_VALIDATE_INT)) 
			throw new ChybaUzivatele('Částka spoluúčasti není celé číslo!');
		if ($_POST['spoluucast'] < 0)
			throw new ChybaUzivatele('Částka spoluúčasti je záporná!');
	}

	public function validujPlneniVysePlneni() :void
	{
		if ($_POST['vyse_plneni'] == '')
			throw new ChybaUzivatele('Výše plnění není vyplněna!');
		if (!filter_var($_POST['spoluucast'], FILTER_VALIDATE_INT)) 
			throw new ChybaUzivatele('Částka plnění není celé číslo!');
		if ($_POST['spoluucast'] < 0)
			throw new ChybaUzivatele('Částka plnění je záporná!');
	}

	public function validujPlneniDatumSchvaleni() : void
	{
		$datum_nahlaseni = DateTime::createFromFormat('j.n.Y', $_POST['datum_nahlaseni']);
		$datum_schvaleni = DateTime::createFromFormat('j.n.Y', $_POST['datum_schvaleni']);

		if ($_POST['datum_schvaleni'] == '')
			throw new ChybaUzivatele('Datum schválení není vyplněno!');
		if (!preg_match('/(^\d{1,2}\.\d{1,2}\.\d{4}$)|(^\d{1,2}\.\s\d{1,2}\.\s\d{4}$)/', $_POST['datum_schvaleni'])) 
			throw new ChybaUzivatele('Datum schválení nemá správný formát!');
		if ($datum_nahlaseni > $datum_schvaleni)
			throw new ChybaUzivatele('Datum schválení předchází datu nahlášení pojistné události!');
	}

	public function validujPlneniDatumVyplaty() : void
	{
		$datum_schvaleni = DateTime::createFromFormat('j.n.Y', $_POST['datum_schvaleni']);
		$datum_vyplaty = DateTime::createFromFormat('j.n.Y', $_POST['datum_vyplaty']);

		if (!preg_match('/(^\d{1,2}\.\d{1,2}\.\d{4}$)|(^\d{1,2}\.\s\d{1,2}\.\s\d{4}$)/', $_POST['datum_vyplaty'])) 
			throw new ChybaUzivatele('Datum výplaty nemá správný formát!');
		if ($datum_schvaleni > $datum_vyplaty)
			throw new ChybaUzivatele('Datum výplaty předchází datu schválení výše plnění!');
	}
	
}