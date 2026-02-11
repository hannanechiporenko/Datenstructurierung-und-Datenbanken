# Books
CREATE TABLE `library`.`books` ( `ID` INT(11) NOT NULL AUTO_INCREMENT , `title` VARCHAR(50) NOT NULL , `description` VARCHAR(255) NOT NULL , `publishing_year` INT(4) NOT NULL , `publisher_id` INT(11) NOT NULL , PRIMARY KEY (`ID`));

# Publisher
CREATE TABLE `library`.`publisher` ( `ID` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(50) NOT NULL , PRIMARY KEY (`ID`));

# Testdaten
INSERT INTO publisher (title) VALUES ('Ein Verlag'), ('Der andere Verlag'), ('Noch ein dritter Verlag');

INSERT INTO books (title, description, publishing_year, publisher_id) VALUES ('Harry Potter und ein Stein', 'Lorem ipsum dolor sit amet', 1956, 2), ('Herr der Ringer 4', 'Lorem ipsum dolor sit amet', 2002, 1), ('Mary Poppins', 'Lorem ipsum dolor sit amet', 1873, 3), ('Kylo & die Gefährten', 'Lorem ipsum dolor sit amet', 2023, 1), ("Schreiber's Naturarium", 'Lorem ipsum dolor sit amet', 2020, 2);