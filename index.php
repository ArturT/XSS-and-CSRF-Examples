<?php

/*
 * Dołączamy plik z funkcjami wyświetlającymi szablon HTML.
 */
include('szablon.php');


head('Strona www');


	if($_GET['cmd'] == 'szukaj')
	{
		/**
		 * Wyświetlamy wyszukiwarkę. Deklaracja funkcji na dole pliku.
		 */
		menu_szukaj();	
	}
	else
	{
		/**
		 * Strona index.php wyświetla domyślnie linki do poszczególnych menu
		 */
		menu_glowne();
		
	}
	
	
footer();


//=== Funkcja dla tego pliku ===============================================================
//==========================================================================================


/**
 * Funkcja wyświetlająca menu główne po wejściu na stronę index.php
 */
function menu_glowne()
{
	echo '
	<h1>Menu główne</h1>
	
	<a href="index.php?cmd=szukaj">Szukaj</a><br /><br />
	<a href="login.php">Logowanie i zmiana hasła</a><br /><br />
	<a href="parser_znakow.php">Parser znaków URL</a><br /><br />
	';
}


//==========================================================================================


/**
 * Funkcja wyświetlająca wyszukiwarkę i obsługująca cały jej mechanizm.
 */
function menu_szukaj()
{
	/**
	 * Pole wyszukiwania
	 * 
	 * Wskazówka: W formularzu mamy ukryte pole o nazwie cmd i wartości szukaj. 
	 * Jest ono potrzebne ponieważ mechanizm naszej wyszukiwarki znajduje się pod adresem: index.php?cmd=szukaj
	 * Po wysłaniu formularz z szukaną frazą otworzy się strona: index.php?cmd=szukaj&fraza=szukana+fraza 	 
	 */
	echo 'Wpisz szukaną frazę.
	
	<form action="index.php" method="GET">
	<input type="hidden" name="cmd" value="szukaj">
	<input style="width:50%;" type="text" name="fraza" value="'.$_GET['fraza'].'" /> 
	<input type="submit" name="submit" value="szukaj" />	
	</form>
	';
	//'.htmlspecialchars($_GET['fraza']).'

	
	// Jeśli przesłano metodą GET fraze do wyszukiwania to wyświetlamy wyniki wyszukiwania dla tej frazy
	if(isset($_GET['fraza']))
	{
		/**
		 * Tutaj powinien być kod jakiegoś mechanizmu pobierającego wyniki znalezionych artykułów w bazie		 
		 */
		echo '<br />
		<span class="bold">Znalezione wyniki:</span><br />
		
		Artykuł 1<br />
		Artykuł 2<br />
		Artykuł 3<br />
		';
	}
	
}

		
?>