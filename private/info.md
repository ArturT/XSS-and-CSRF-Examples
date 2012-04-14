# Info

W katalogu `private` będą przetrzymywane zaszyfrowane hasła użytkowników oraz sesje zalogowanych osób.
Aby nikt z zewnątrz nie miał dostępu do nich należy przy pomocy pliku `.htaccess` zablokować
możliwość przeglądania tego katalogu. Najlepszym rozwiązaniem jest umieszczenie katalogu poza ścieżką dostępną publicznie.


### Plik .htaccess powinien zawierać treść:

	order allow,deny
	deny from all
	
	