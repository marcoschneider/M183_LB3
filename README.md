# M183 Todo App

## Requirements (Composer & NPM)
### Google Authy
Damit diese Todo App korrekt funktioniert, brauchen Sie die App _Authy_ von Google welche im [Google Play Store](https://play.google.com/store/apps/details?id=com.authy.authy) oder im [App Store](https://itunes.apple.com/us/app/authy/id494168017) heruntergelaen werden kann.

### Composer
Falls Sie [Composer](https://getcomposer.org/download/) nicht installiert haben, wird empfohlen dies noch zu tun. Nach dem Download können mit Composer die fehlenden Dependencies herunterladen. Dies wird mit dem Folgenden Befehl gemacht:
````bash
cd ~/Path/to/Document Root/M183_LB3/
php composer.phar install

//Konsolen Output 
Package operations: 1 install, 0 updates, 0 removals
  - Installing robthree/twofactorauth (1.6.5): Loading from cache
Writing lock file
Generating autoload files
```` 

Nun sollte im Root Ordner der App ein neuer Ordner namens _vendor_ und dort sind nun alle Dependencies drin, welche mit composer installiert wurden.

Wichtig ist das im _vendor_ Verzeichnis das File _autoload.php_ vorhanden ist.

### NPM & Bower
#### npm
Falls Sie npm noch nicht installier haben, wird empfohlen dies noch zu tun. Wie Sie npm installieren wird [hier](https://www.npmjs.com/get-npm) erklärt. Nach der Installation können diesen Befehl ausführen, um die JS Dependencies herunter zu laden:
````bash
cd ~/Path/to/Document Root/M183_LB3/
npm install
````

####bower
Bower können Sie mit dem folgenden npm Befehl installieren:
```bash
npm install -g bower
```

## Ohne Composer & NPM
Im Zipordner sind bereits alles Dependencies vorhanden. Laden Sie den Zip Ordner herunter. Entpacken Sie ihn und ziehen Sie den entzippten Ordner in Ihr Document Root Verzeichnis. Danach kann das Projekt via [localhost/M183_LB3](localhost/M183_LB3) geöffnet werden.

## Datenbank
Im ZIP Ordner gibt es einen SQL DUMP welchen Sie bei Ihnen importieren. Beachten Sie das File `settings.example.php` zu kopieren und auf `settings.local.php` umbenennen. Zusätzlich passen Sie die Datenbank Benutzerdaten an und ebenfalls die `$settings['domain']` zu ändern (Bei Localhost 'localhost' einfügen). 