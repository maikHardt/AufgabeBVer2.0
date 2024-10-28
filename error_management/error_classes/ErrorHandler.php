<?php
// Lädt die Fehlernachrichten einmalig, sobald die Klasse verwendet wird.
ErrorHandler::loadMessages();

class ErrorHandler {    
    // Ein Array zum Speichern der geladenen Fehlernachrichten.
    private static $errorMessages = []; 

    // Methode zum Laden der Fehlernachrichten aus der error_messages.php Datei.
    public static function loadMessages() {
        // Fehlernachrichten laden und im Array $errorMessages speichern.
        self::$errorMessages = include '../error_management/error_messages.php';
    }

    // Methode, um eine spezifische Fehlermeldung anhand eines Schlüssels abzurufen.
    public static function getError($key) {
        // Prüft, ob eine Fehlermeldung für den angegebenen Schlüssel existiert.
        // Wenn ja, wird die Nachricht zurückgegeben; wenn nein, eine Standardmeldung.
        return isset(self::$errorMessages[$key]) ? self::$errorMessages[$key] : 'Keine Fehlernachricht gefunden aber ein Fehler existiert';
    }
    
    // Methode zur Anzeige einer spezifischen Fehlermeldung im HTML-Format, basierend auf einem Schlüssel.
    public static function displayErrors(string $key) {
        // Überprüft, ob es im Session-Array 'errors' eine Fehlermeldung für den angegebenen Schlüssel gibt.
        if (isset($_SESSION['errors'][$key])) {
            // Gibt die Fehlermeldung als HTML-Absatz aus und formatiert sie in roter Farbe.
            echo '<p style="color:red;">' . $_SESSION['errors'][$key] . '</p>';
            
            // Entfernt die Fehlermeldung aus der Session, nachdem sie angezeigt wurde.
            unset($_SESSION['errors'][$key]);
        }
    }    
}
?>
