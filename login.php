<?php

// Sekretny klucz. Będzie używany jako ziarno do szyfrowanych haseł.
define('SECRET_KEY', 'GK)_)^$246!@BJUERVU%&Rdqknztq63er863!@$!#@$!#');

/*
 * Dołączamy plik z funkcjami wyświetlającymi szablon HTML.
 */
include('szablon.php');

// funkcja wyloguje użytkownika tylko gdy wejdzie na stronę login.php?cmd=wyloguj
logout();

/**
 * Pod adresem login.php?cmd=zaloguj będziemy wykonywać logowanie danymi przesłanymi z formularza logowania.
 * 
 * Funkcja login() jest wywołana nad funkcją head() ponieważ ustawienie ciasteczek musi nastąpić przed wysłaniem nagłówków do przeglądarki
 * (tzn. Nie można ustawiać ciasteczek z poziomu PHP jeśli wydrukowaliśmy wcześniej metodą echo jakieś znaki 
 * lub jeśli ustawiliśmy funkcją header(), która jest wbudowana w PHP, nagłówki strony)
 */
if($_GET['cmd']=='zaloguj')
{
	$czy_zalogowano = login(); // funkcja zwraca TRUE jeśli zalogowano pomyślnie
}

head('Logowanie i zmiana hasła');
	
	// Jeśli użytkownik jest zalogowany to wyświetlamy link do opcji wyloguj 
	if(is_user_logged())	
	{
		echo '<div style="float:right;"><a href="login.php?cmd=wyloguj">wyloguj się</a></div>';
	}
	
	
	
	if($_GET['cmd']=='zmiana_hasla')
	{
		change_password(); // wywołanie funkcji obsługującej zmianę hasła
	}
	elseif($_GET['cmd']=='zaloguj')
	{
		// Komunikat czy udało się zalogować.
		if($czy_zalogowano) 
		{
			echo '<span class="success">Pomyślnie zalogowano.</span> <a href="login.php?cmd=zmiana_hasla">Przejdź do zmiany hasła</a>';
		}
		else
		{
			echo '<span class="error">Nie udało się zalogować.</span> <a href="login.php">Ponów próbę</a>';
		}
		
		// Podgląd klucza tylko na potrzeby ćwiczenia.
		echo '<br /><br />Klucz dla hasła, które wpisaliśmy w formularzu: '.$wygenerowany_klucz;
	}
	elseif(is_user_logged())
	{
		echo '<span class="success">Witaj, '.$_COOKIE['login_name'].'. Jesteś zalogowany.</span> <a href="login.php?cmd=zmiana_hasla">Przejdź do zmiany hasła</a>';		
	}		
	else
	{
		/**
		 * Jeśli użytkownik jest wylogowany to wyświetlamy formularz logowania
		 */
		echo '<h1>Logowanie</h1>
		
		<form action="login.php?cmd=zaloguj" method="post">
		Login: <input type="text" name="login" /><br />
		Hasło: <input type="password" name="password" /><br />
		<input type="submit" name="submit" value="zaloguj" />
		</form>';
	}
	
	
footer();


//=== Funkcja dla tego pliku ===============================================================
//==========================================================================================


/**
 * Funkcja sprawdzająca czy użytkownik jest zalogowany
 * Zwraca TRUE/FALSE
 */
function is_user_logged()
{
	// nazwa loginu powinna być odfiltrowana ponieważ bedzie używana w ścieżce do pliku!!
	// Na potrzeby prezentacji pominiemy to zabezpieczenie...
	$login = $_COOKIE['login_name'];  
	$session = $_COOKIE['session_key'];
	
	$file_session_location = 'private/sessions/'.$login.'.txt';
	
	// Sprawdzamy czy istnieje plik $file
	if(is_file($file_session_location)) 
	{
		$tab = file($file_session_location);
		
		// Jeśli sesja zapisana po stronie serwera tzn. w pliku .txt jest taka sama jak ta w ciasteczku zapisanym w przeglądarce użytkownika 
		// to znaczy że użytkownik jest poprawnie zalogowany.
		if($tab[0] == $session)
		{
			return TRUE;
		}
		else
		{	
			return FALSE;
		}
	}
	else
	{	
		return FALSE;
	}
}


//==========================================================================================


/**
 * Funkcja ta odpowiada za sprawdzenie czy dane przesłane formularzem umożliwiają zalogowanie.
 * Jeśli tak to ustawiamy ciasteczka 
 */
function login()
{
	/**
	 * Zmienna globalna. W niej zapiszemy klucz wygenerowany na podstawie hasła. 
	 * Przyda się na wypadek gdybyśmy chcieli ręcznie dodać pliki z nowymi użytkownikami.
	 */
	global $wygenerowany_klucz; 
	
	if(isset($_POST['submit']))
	{
		// Funkcja trim() usuwa białe znaki z początku i końca stringa.
		// Zmienną login powinno się dodatkowo odfiltrować przed niechcianymi znakami np. apostrofy itp. 
		// Można by np. przy użyciu wyrażeń regularnych zezwalać tylko na podawanie loginu zawierającego znaki od a do Z
		// Na potrzeby przykładu pominiemy takie zabezpieczenia.
		$login = trim($_POST['login']); 
		$password = trim($_POST['password']);
		
		/**
		 * Szyfrujemy hasło połączone z sekretnym kluczem algorytmem sha1. 
		 * Utrudni to odszyfrowanie haseł w przypadku wycieku zaszyfrowanych kluczy.
		 */
		$password_sha1 = sha1($password.SECRET_KEY);
		
		/**
		 * do $wygenerowany_klucz przypisujemy klucz hasła wpisanego w formularzu 
		 * (na potrzeby przykładu przyda się aby móc podejrzeć wygenerowane klucze dla różnych haseł)		
		 */ 
		$wygenerowany_klucz = $password_sha1; 
		
		
		/**
		 * Sprawdzamy czy uzytkownik o danym loginie ma takie samo hasło jak to wpisane w formularzu.
		 * W katalogu private/passwords/ znajduje się plik .txt o nazwie będącej loginem użytkownika.
		 * Plik ten zawiera klucz dla hasła użytkownika.
		 * Jeśli klucz z pliku jest równy kluczowi wygenerowanemu na podstawie hasła z formularza to
		 * znaczy że podczas logowania zostały podane poprawne dane i użytkownik może zostać zalogowany
		 */
		$file_location = 'private/passwords/'.$login.'.txt';
		$file_session_location = 'private/sessions/'.$login.'.txt';
		
		// Sprawdzamy czy istnieje plik $file_location
		if(is_file($file_location)) 
		{			 
			// $tab staje się tablicą zawierającą wszystkie linie pliku 	
			$tab = file($file_location);
			# UWAGA! Na końcu każdej linii w pliku jest znak \n chyba że plik jest jednolinijkowy. 
			# Może to powodować błędy np. Jeśli plik .txt będzie składał się z dwóch linii to porówanie
			# if($tab[0] == $password_sha1) będzie zawsze fałszywe gdyż $tab[0] będzie zawierać niewidoczny znak nowej linii. 
			
			
			// Na pierwszej linii mamy zapisany klucz dla użytkownika którego login to nazwa pliku .txt			
			if($tab[0] == $password_sha1)
			{
				// klucz z formularza okazał się taki sam jak w pliku private/passwords/login_uzytkownika.txt
				// możemy wygenerować unikatową sesję dla użytkownika i zapisać ją w katalogu sesji
				
				// generujemy jakis unikatowy klucz sesji na podstawie daty, losowej liczby, sekretnego klucza i microsekund 
				$session_key = sha1(date('Y-m-d H:i:s').rand(0,99999999999).SECRET_KEY.microtime());
				 
				// klucz sesji zapisujemy do ciasteczka w przeglądarce użytkownika na okres 6 godzin.
				// Wskazówka: Budując system logowania z prawdziwego zdarzenia ważność sesji powinniśmy ustawić na krótszy czas 
				// i odnawiać ją np. przy każdej kolejnej odsłonie witryny.
				setcookie('session_key', $session_key, time()+3600*6);
				setcookie('login_name', $login, time()+3600*6); // Ustawiamy ciasteczko z nazwą użytkownika abyśmy wiedzieli kto jest zalogowany.
				
				// Klucz sesji musimy również zapisać do katalogu private/sessions/
				// Wskazówka: Budując system logowania z prawdziwego zdarzenia ważność sesji dla danego użytkownika należy przetrzymywać 
				// w bazie danych i mieć ją powiązaną z danym ID użytkownika. Należy również odnawiać sesję przy kolejnych odsłonach stron
				// tak samo jak odnawiamy ważność ciasteczka session_key.
				$file_session = fopen($file_session_location, 'w');
				fwrite($file_session, $session_key);
				fclose($file_session);
				
				
				### Ciekawostka:
				// Wskazówka odnośnie projektowania logowania na bazie danych.
				// Jeśli w przeglądarce użytkownika jest zapisana sesja wraz z ID użytkownika, a także występuje ona w bazie danych to na tej
				// podstawie wiemy, że użytkowik jest zalogowany. Klucze sesji w pliku cookie i w bazie danych muszą być takie same i być przypisane
				// do tego samego ID użytkownika.
				// Aby wylogować użytkownika wystarczy że usuniemy sesje z bazy danych.
				// Usunięcie samego ciasteczka nie powoduje  
							
				
				return TRUE; //poprawnie zalogowano
			}
			else
			{
				return FALSE; // Niepoprawny klucz. Nie można zalogować.								
			}
			
		
		}
		else
		{
			// plik nie istnieje. Nie ma takiego użytkownika. Zwracamy FALSE bo nie można zalogować poprawnie.
			return FALSE;
		}	
	
	}
	else
	{
		return FALSE; // nie udało się zalogować ponieważ nie przesłano danych do logowania metodą POST
	}
}


//==========================================================================================


/**
 * Funkcja odpowiedzialna za generowanie formularza do zmiany hasła
 */
function change_password()
{
	if(is_user_logged())
	{
		// Jeśli tablica $_POST zawiera jakieś elementy to znaczy że przesłano dane formularzem.
		if(count($_POST)) 
		{
			$password_1 = trim($_POST['password_1']);
			$password_2 = trim($_POST['password_2']);
			
			// Jeśli przesłane nowe hasła są takie same to można ustawić nowe hasło
			if($password_1 == $password_2) //AND $_COOKIE['session_key'] == $_POST['session_key'] # Należy dodać sprawdzanie tokenu sesji aby w pełni zabezpieczyć formularz.
			{
				//generowanie klucza dla nowego hasła
				$password_sha1 = sha1($password_1.SECRET_KEY);
			
				//Na żywca lepiej nie wkładać ciasteczka do ścieżki. Trzeba by odfiltrować dane loginu.
				$plik_adres = 'private/passwords/'.$_COOKIE['login_name'].'.txt';
				$file = fopen($plik_adres, 'w');
				fwrite($file, $password_sha1);
				fclose($file);
				
				echo '<span class="success">Pomyślnie zmieniono na nowe hasło!</span>';
			}
			else
			{
				echo '<span class="error">Błąd. Nie ustawiono nowego hasła!</span>';
			}
		}
		else
		{
			// Wyświetlanie formularza zmiany hasła.
			echo 'Formularz zmiany hasła. Podaj nowe hasło:<br /><br />
			
			<form action="login.php?cmd=zmiana_hasla" method="POST">			
			Nowe hasło:<br />
			<input type="password" name="password_1" value="" /><br />
			Powtórz:<br />
			<input type="password" name="password_2" value="" /><br />						
			<input type="submit" name="button_submit" value="zmień hasło" />

			<!-- Przekazywanie tokenu sesji. -->
			<input type="hidden" name="session_key" value="'.$_COOKIE['session_key'].'" />	
			
			</form>
			';
			//<input type="hidden" name="session_key" value="'.$_COOKIE['session_key'].'" />
		}
	
	}
	else
	{
		echo 'Jesteś wylogowany! Proszę się zalogować.';
	}
}


//==========================================================================================


/**
 * Funkcja wylogowująca użytkownika.
 */
function logout()
{
	if($_GET['cmd'] == 'wyloguj')
	{
		//Na żywca lepiej nie wkładać ciasteczka do ścieżki. Trzeba by odfiltrować dane loginu.
		$plik_sesji = 'private/sessions/'.$_COOKIE['login_name'].'.txt';
		 
		// kasujemy sesje po stronie serwera. Usuwamy plik .txt sesji
		@unlink($plik_sesji);
		
		//czyścimy wartości ciasteczek z sesją użytkownika i loginem
		setcookie('session_key', NULL, time()-1);
		setcookie('login_name', NULL, time()-1);		
		
	}	
}

?>