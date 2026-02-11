📚 Bibliotheksverwaltungssystem
(CRUD für Bücher – PHP + MySQL + JavaScript)

📖 Beschreibung
Dies ist eine einfache Webanwendung zur Verwaltung einer Bibliothek.
Folgende Funktionen werden unterstützt:
➕ Buch erstellen
✏️ Buch bearbeiten
🗑 Buch löschen
📋 Alle Bücher anzeigen

Das Projekt wurde entwickelt mit:
PHP (Backend)
MySQL (Datenbank)
Bootstrap 5 (Design)
JavaScript (Bestätigung + visuelle Effekte)

🧠 Funktionsweise
1️⃣ Buch erstellen (INSERT)
Wenn das versteckte Feld id = 0 ist, wird folgender SQL-Befehl ausgeführt:
INSERT INTO books (...)
Eine neue Buch-Datensatz wird in der Datenbank gespeichert.
2️⃣ Buch bearbeiten (UPDATE)
Wenn id > 0 ist, wird ausgeführt:
UPDATE books SET ... WHERE id = ?
Der bestehende Datensatz wird aktualisiert.
3️⃣ Buch löschen (DELETE)
Beim Klick auf die Löschen-Schaltfläche:
<a href="?delete=ID">
wird folgender SQL-Befehl ausgeführt:
DELETE FROM books WHERE id = ?
Vor dem Löschen erscheint eine JavaScript-Bestätigung:
onclick="return confirm('Wirklich löschen?');"

🗂 Projektstruktur
/library
│
├── index.php
├── snowfall.js
├── bootstrap-5.3.7-dist/
└── database.sql

🗄 Datenbankstruktur
Tabelle publisher
id	title
Tabelle books
| id | title | description | publishing_year | publisher_id |
Beziehung:
books.publisher_id → publisher.id
(Fremdschlüssel)

⚙️ Installation
Projekt in den Ordner htdocs (XAMPP) kopieren
Datenbank library erstellen
Tabellen importieren
Im Browser öffnen:
http://localhost/library/index.php

🔐 Sicherheit
Verwendung von Prepared Statements
Schutz vor XSS mit htmlspecialchars()
Aktivierter Entwicklungsmodus für Fehlermeldungen

🌨 JavaScript-Funktionalität
JavaScript wird verwendet für:
Bestätigung beim Löschen
Schneefall-Animation (snowfall.js)
JavaScript läuft im Browser und greift nicht direkt auf die Datenbank zu.

📌 Technologien
PHP 8+
MySQL
Bootstrap 5
Vanilla JavaScript

🎯 CRUD-Übersicht
Aktion	SQL-Befehl
Create	INSERT
Read	SELECT
Update	UPDATE
Delete	DELETE

🚀 Mögliche Erweiterungen
Suchfunktion
Pagination
Benutzer-Authentifizierung
CSRF-Schutz
MVC-Struktur
REST-API
