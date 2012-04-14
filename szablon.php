<?php

/**
 * Plik zawierający funkcję odpowiedzialne za wyświetlenie górnej i dolnej części szablonu HTML.
 * Funkcja head przyjmuje jako argument $title pozwalając ustalić nam własny tytuł dla strony.
 */
function head($title)
{
	echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>'.$title.'</title>	
		<link rel="stylesheet" href="css/style.css" type="text/css" />	
	</head>
	
	<body>
	<div id="container">';
}

/**
 * Generowanie stopki szablonu HTML.
 */
function footer()
{
	echo '
	</div>
	<div id="bottom_menu">
		<a href="index.php">Powrót do menu głównego</a>
	</div>
	</body>
	</html>
	';
}
		
?>