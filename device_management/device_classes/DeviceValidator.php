<?php
require_once '../error_management/error_classes/ErrorHandler.php'; // Importiert den ErrorHandler für das Fehlerhandling

class DeviceValidator {
    private static $errorMessages = []; // Array, um Fehlermeldungen zu speichern

    // Validiert den Hostnamen anhand eines regulären Ausdrucks
    public static function validateHostname(string $hostname): string {
        if (!preg_match('/^[a-zA-Z0-9.-]+$/', $hostname)) {
            // Füge eine Fehlermeldung hinzu, wenn der Hostname ungültig ist
            self::$errorMessages['hostname'] = ErrorHandler::getError('hostname');
        }
        return $hostname; // Gibt den Hostnamen zurück
    }

    // Validiert die Domain mit PHPs eingebautem Filter
    public static function validateDomain(string $domain): string {
        if (!filter_var($domain, FILTER_VALIDATE_DOMAIN)) {
            // Füge eine Fehlermeldung hinzu, wenn die Domain ungültig ist
            self::$errorMessages['domain'] = ErrorHandler::getError('domain');
        }
        return $domain; // Gibt die Domain zurück
    }

    // Validiert die IP-Adresse mit PHPs eingebautem Filter
    public static function validateIpAddress(string $ipAddress, bool $dhcp): string {
        if($dhcp == false) {
            if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
                // Füge eine Fehlermeldung hinzu, wenn die IP-Adresse ungültig ist
                self::$errorMessages['ip_address'] = ErrorHandler::getError('ip_address');
            }
            return $ipAddress; // Gibt die IP-Adresse zurück
        }
        return $ipAddress;     
    }

    // Validiert die MAC-Adresse anhand eines regulären Ausdrucks
    public static function validateMacAddress(string $macAddress): string {
        if (!preg_match('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', $macAddress)) {
            // Füge eine Fehlermeldung hinzu, wenn die MAC-Adresse ungültig ist
            self::$errorMessages['mac_address'] = ErrorHandler::getError('mac_address');
        }
        return $macAddress; // Gibt die MAC-Adresse zurück
    }

    // Validiert das Passwort auf eine Mindestlänge
    public static function validatePassword(string $password): string {
        if (strlen($password) < 6) {
            // Füge eine Fehlermeldung hinzu, wenn das Passwort zu kurz ist
            self::$errorMessages['password'] = ErrorHandler::getError('password');
        }
        return $password; // Gibt das Passwort zurück
    }

    // Validiert die Beschreibung, gibt eine Standardbeschreibung zurück, wenn sie leer ist
    public static function validateDescription(string $description): string {
        return empty($description) ? 'Keine Beschreibung' : $description; // Rückgabe der Beschreibung oder Standardwert
    }

    // Überprüft, ob die Werte in der CSV-Datei eindeutig sind
    public static function validateUniqueValues(string $hostname, string $domain, string $macAddress, string $username, string $csvPath): bool {
        // Öffne die CSV-Datei zum Lesen
        if (($handle = fopen($csvPath, "r")) !== FALSE) {
            // Flag für Fehler
            $hasErrors = false;
    
            // Gehe durch jede Zeile der CSV-Datei
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Überprüfe, ob der Hostname bereits existiert
                if ($data[0] === $hostname) {
                    self::$errorMessages['exists_hostname'] = ErrorHandler::getError('exists_hostname');
                    $hasErrors = true; // Setze Flag auf true, wenn ein Fehler gefunden wurde
                }
                // Überprüfe, ob die Domain bereits existiert
                if ($data[1] === $domain) {
                    self::$errorMessages['exists_domain'] = ErrorHandler::getError('exists_domain');
                    $hasErrors = true; // Setze Flag auf true, wenn ein Fehler gefunden wurde
                }
                // Überprüfe, ob die MAC-Adresse bereits existiert
                if ($data[4] === $macAddress) {
                    self::$errorMessages['exists_mac_address'] = ErrorHandler::getError('exists_mac_address');
                    $hasErrors = true; // Setze Flag auf true, wenn ein Fehler gefunden wurde
                }
                // Überprüfe, ob der Benutzername bereits existiert
                if ($data[5] === $username) {
                    self::$errorMessages['exists_username'] = ErrorHandler::getError('exists_username');
                    $hasErrors = true; // Setze Flag auf true, wenn ein Fehler gefunden wurde
                }
            }
            fclose($handle); // Schließt die Datei
            
            // Gibt false zurück, wenn Fehler gefunden wurden, ansonsten true
            return !$hasErrors;
        } else {
            self::$errorMessages['csv_data'] = ErrorHandler::getError('csv_data'); // Fehler, wenn die Datei nicht geöffnet werden kann
            return false;
        }
    }
    

    // Gibt alle gesammelten Fehlermeldungen zurück
    public static function getErrorMessages(): array {
        return self::$errorMessages;
    }

    // Löscht alle gespeicherten Fehlermeldungen
    public static function clearErrors() {
        self::$errorMessages = []; // Setzt das Fehler-Array zurück
    }
}
