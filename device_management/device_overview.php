<?php
session_start(); // Startet die Session, um Benutzerdaten zu verwalten
// Definiert den Pfad zur CSV-Datei
$filename = 'device_data/data.csv';
$devices = []; // Initialisiert ein leeres Array, um die Netzwerkinformationen zu speichern

// Überprüft, ob die Datei existiert und öffnet sie zum Lesen
if (file_exists($filename) && ($handle = fopen($filename, 'r')) !== FALSE) {
    // Liest die Datei zeilenweise und speichert jede Zeile als Array im $netzwerke-Array
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $devices[] = $data; // Fügt die gelesene Zeile zum $netzwerke-Array hinzu
    }
    if ($handle === false) {
        ErrorHandler::get_error('1'); // Datei konnte nicht erstellt werden
    }
    fclose($handle); // Schließt die Datei

    // Entfernt die erste Zeile, falls diese die Kopfzeile ist
    array_shift($devices); // Entfernt die Kopfzeile aus dem Array

    if (count($devices) === 1 && empty($devices[0][0])) {
        // Wenn das Array so aus sieht: Array([0] => Array([0] => )) als Beispiel: Die Daten der CSV Datei beschränkt sich ausschließlich nur auf die Kopfzeile
        $devices = []; // Dann Leere das Array
    }
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/device_overview.css">
    <title>Überblick</title>    
</head>
<body>
    <h1>Netzwerk Übersicht</h1> <!-- Überschrift der Seite -->
    <div id="div_back"> <!-- Div für den Zurück-Button -->
        <form method="get" action="../"> <!-- Formular, das den Benutzer zur Hauptseite zurückführt -->
            <button type="submit">Zurück zur Hauptseite</button> <!-- Button zum Zurückkehren -->
        </form>
    </div> 
    <?php if (!empty($devices) || $devices == ""): ?> <!-- Überprüft, ob es Netzwerkinformationen gibt -->
        <?php foreach ($devices as $device): ?> <!-- Iteriert über jedes Netzwerk -->
            <div class="div_devices"> <!-- Div für die Netzwerkdetails -->
                <h3>Hostname: <?php echo htmlspecialchars($device[0]); ?></h3> <!-- Zeigt den Hostnamen an -->
                <p>Domain: <?php echo htmlspecialchars($device[1]); ?></p> <!-- Zeigt die Domain an -->
                <?php // Zeig die IP-Adresse nur an wenn DHCP deaktiviert ist
                if($device[3] == 'No') {
                     ?> <p>IP-Addresse: <?php echo htmlspecialchars($device[2]); ?></p></p> <?php
                }
                ?>
                <p>DHCP: <?php echo htmlspecialchars($device[3]); ?></p> <!-- Zeigt die IP-Adresse an -->
                <p>MAC-Adresse: <?php echo htmlspecialchars($device[4]); ?></p> <!-- Zeigt die MAC-Adresse an -->
                <p>Benutzername: <?php echo htmlspecialchars($device[5]); ?></p> <!-- Zeigt den Benutzernamen an -->
                <p>Beschreibung: <?php echo htmlspecialchars($device[7]); ?></p> <!-- Zeigt die Beschreibung an -->
                
                <!-- Könnte man machen: Passwortverifizierung implementieren -->
                <!-- Überprüfen, ob der Benutzer authentifiziert ist -->
                <!-- Wenn ja, das Passwort anzeigen, andernfalls eine Nachricht anzeigen -->
                <!-- Beispiel: 
                if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
                    echo '<p>Passwort: ' . htmlspecialchars($device[6]) . '</p>';
                } else {
                    echo '<p>Passwort: Keine Zugriffsrechte </p>';
                }
                -->
            </div>
        <?php endforeach; ?>
    <?php else: ?> <!-- Wenn keine Netzwerke gefunden werden -->
        <p>Keine Netzwerke gefunden.</p> <!-- Nachricht anzeigen -->
    <?php endif; ?>
</body>
</html>
