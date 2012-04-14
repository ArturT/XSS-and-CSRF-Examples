<?php

/*
 * Dołączamy plik z funkcjami wyświetlającymi szablon HTML.
 */
include('szablon.php');


head('Strona www');

	
	echo '<h1>Parsowanie znaków</h1>
	Wpisz kod w którym znaki mają być zamienione na odpowiednie znaki akceptowane w adresach URL.
	
	<form action="parser_znakow.php" method="POST">	
	<textarea wrap="off" style="width:100%;height:200px;" name="kod">'.$_POST['kod'].'</textarea>
	<input type="submit" name="submit" value="parsuj" />		
	</form>
	';
	
	if($_POST['kod'])
	{
		/**
	 	* Funkcja http://pl.php.net/manual/pl/function.rawurlencode.php umożliwia zakodowanie znaków 
	 	* na takie które można przesyłać w adresach URL. Aby przesłać znaki w UTF-8 potrzebna będzie 
	 	* mała modyfikacja tej funkcji, którą możemy znaleźć na php.net
	 	* Deklaracja funkcji mb_rawurlencode na dole tego pliku.
	 	*/
		$text = trim($_POST['kod']); // usuwamy białe znaki z początku i końca tekstu
		$text = preg_replace('/\s+/', ' ', $text); // usuwamy znaki nowej linii z całego tekstu.
		$text = mb_rawurlencode($text); // parsujemy tekst
		
		echo '<h1>Kod po parsowaniu</h1><textarea wrap="off" style="width:100%;height:100px;">'.$text.'</textarea>';
	}
	
	
footer();


//=== Funkcja dla tego pliku ===============================================================
//==========================================================================================


/**
 * Funkcja rawurlencode która obsługuje znaki w kodowaniu UTF-8
 * Źródło: http://pl.php.net/manual/pl/function.rawurlencode.php#100313
 */
function mb_rawurlencode($url)
{
	$encoded = '';
	$length = mb_strlen($url);
	for($i=0; $i<$length; $i++)
	{
		$encoded .= '%'.wordwrap(bin2hex(mb_substr($url,$i,1)),2,'%',true);
	}
	return $encoded;
}
		
?>