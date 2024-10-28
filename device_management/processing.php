<?php
// Die benötigten Klassen für Geräteintegration und -validierung werden geladen.
require_once 'device_classes/DeviceIntegrator.php';
require_once 'device_classes/DeviceValidator.php';

// Startet eine PHP-Session, um Sessions zu nutzen und Werte zwischen Seitenaufrufen zu speichern.
session_start();

// Überprüft, ob die Anfrage eine POST-Anfrage ist, um sicherzustellen, dass Daten über ein Formular gesendet wurden.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Überprüft, ob ein CSRF-Token in den POST-Daten vorhanden ist und ob es mit dem in der Session gespeicherten Token übereinstimmt.
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Ungültiges CSRF-Token.'); // Beendet das Skript, wenn das CSRF-Token ungültig ist.
    }

    try {
        // Erstellt ein neues `DeviceIntegrator`-Objekt, das die Daten aus dem POST-Array verarbeitet.
        $deviceIntegrator = new DeviceIntegrator($_POST);
        
        // Erstellt ein neues `DeviceValidator`-Objekt, um die Formulardaten zu validieren.
        $deviceValidator = new DeviceValidator;
        
        // Holt die Fehlermeldungen aus dem Validator und speichert sie in der Session.
        $_SESSION['errors'] = $deviceValidator->getErrorMessages();
        
        // Prüft, ob keine Fehler vorliegen (leeres Fehlerarray).
        if (!$_SESSION['errors']) {
            //Wenn keine Fehler vorhanden sind
            // Optional: Speichert die Daten in einer CSV-Datei, wenn keine Fehler vorliegen.
            $deviceIntegrator->saveToCSV('device_data/data.csv');
            
            // Leitet den Benutzer auf die Seite `device_overview.php` weiter, wenn die Speicherung erfolgreich war.
            header('Location: device_overview.php');
        } else {            
            // Wenn Fehler vorhanden sind, leitet die Seite zurück zur `add_device.php`, um das Formular erneut anzuzeigen.
            header('Location: add_device.php'); 
        }
    } catch (Exception $e) {
        // Gibt einen Fehler aus, falls eine Exception geworfen wird und beendet die Funktion.
        return $e;      
    }
} else {
    // Wenn die Anfrage keine POST-Anfrage ist, gibt das Skript eine Fehlermeldung aus.
    echo '<p>Ungültige Anforderung.</p>';
}
?>
