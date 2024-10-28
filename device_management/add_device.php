<?php
/*
Sicherheitshinweis: Wenn man das Formular absendet und einen Fehler gemacht hat, wird man wieder zurückgeschickt.
                    In der Netzwerkanalyse unter der Anfrage von processing.php lässt sich dann das Passwort einsehen.
*/

require_once '../error_management/error_classes/ErrorHandler.php'; // Fehlerbehandlungs-Klasse einfügen
session_start(); // Startet eine neue Session oder setzt die bestehende fort

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Für den Zurück Button
    header("Location: ../");
    exit();
}

// CSRF-Token generieren, um Cross-Site Request Forgery zu verhindern
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generiert einen sicheren zufälligen CSRF-Token
}

/*
echo '<pre>'; 
    print_r($_SESSION['errors']); // Gibt Fehler in der Session zur Debugging-Zwecken aus
echo '</pre>';
*/

$csrf_token = $_SESSION['csrf_token']; // CSRF-Token in der Session für die Überprüfung beim Absenden speichern

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>IT-System Integration</title>
    <link rel="stylesheet" href="../assets/css/add_device.css"> <!-- CSS für das Layout der Seite einfügen -->
</head>
<body>
<div class="container">
    <?php   
        // Überprüft, ob Fehler in der Session für CSV-Daten existieren und gibt sie aus
        if (isset($_SESSION['errors']['1'])) {
            ErrorHandler::displayErrors('1'); // Wenn die Datei sich nicht öffbeb lässt
        } 
    ?>
    <h2>Neues IT-System integrieren</h2>
    <form id="form" action="processing.php" method="POST"> <!-- Formular für die Geräteintegration -->
        <div class="formDiv">
            <div class="inner_container">
                <label for="hostname">Hostname:</label> <!-- Label für Hostname -->
            </div>
            <div class="inner_container">
                <?php
                // Eingabefeld für den Hostname mit Sicherheitsmaßnahmen
                echo '<input type="text" class="form_input" id="hostname" name="hostname" value="' . htmlspecialchars($formData['hostname'] ?? '') . '" required>';
                // Fehlermeldungen für Hostname anzeigen, falls vorhanden
                if (isset($_SESSION['errors']['hostname'])) {
                    ErrorHandler::displayErrors('hostname');
                } else if (isset($_SESSION['errors']['exists_hostname'])) {
                    ErrorHandler::displayErrors('exists_hostname');
                }
                ?>
            </div>
        </div>

        <div class="formDiv">
            <div class="inner_container">
                <label for="domain">Domain:</label> <!-- Label für Domain -->
            </div>
            <div class="inner_container">
                <?php
                // Eingabefeld für die Domain mit Sicherheitsmaßnahmen
                echo '<input type="text" class="form_input" id="domain" name="domain" value="' . htmlspecialchars($formData['domain'] ?? '') . '" required>';
                // Fehlermeldungen für Domain anzeigen, falls vorhanden
                if (isset($_SESSION['errors']['domain'])) {
                    ErrorHandler::displayErrors('domain');
                } else if (isset($_SESSION['errors']['exists_domain'])) {
                    ErrorHandler::displayErrors('exists_domain');
                }
                ?>
            </div>
        </div>
        
        <div class="formDiv">
            <div class="inner_container">
                <label for="dhcp" style="user-select: none;">DHCP verwenden:</label>                
            </div>
            <div class="inner_container">
                <input type="checkbox" id="dhcp" name="dhcp" <?= isset($formData['dhcp']) && $formData['dhcp'] === 'ja' ? 'checked' : '' ?> onchange="toggleIP()">
                <!-- Checkbox für die Verwendung von DHCP -->
            </div>
        </div>

        <div class="formDiv" id="ipFeld">
            <div class="inner_container">
                <label for="ip_adress">IP-Adresse:</label> <!-- Label für IP-Adresse -->
            </div>
            <div class="inner_container">
                <?php
                // Eingabefeld für die IP-Adresse mit Sicherheitsmaßnahmen
                echo '<input type="text" class="form_input" id="ip_address" name="ip_address" value="' . htmlspecialchars($formData['ip_address'] ?? '') . '" placeholder="z.B. 192.168.1.1">';
                // Fehlermeldungen für die IP-Adresse anzeigen, falls vorhanden
                if (isset($_SESSION['errors']['ip_address'])) {
                    ErrorHandler::displayErrors('ip_address');
                }
                ?>
            </div>
        </div>

        <div class="formDiv">
            <div class="inner_container">
                <label for="mac">MAC-Adresse:</label> <!-- Label für MAC-Adresse -->
            </div>
            <div class="inner_container">
                <?php
                // Eingabefeld für die MAC-Adresse mit Sicherheitsmaßnahmen
                echo '<input type="text" class="form_input" id="mac_address" name="mac_address" value="' . htmlspecialchars($formData['mac_adress'] ?? '') . '" required placeholder="z.B. 00:1A:2B:3C:4D:5E">';
                // Fehlermeldungen für MAC-Adresse anzeigen, falls vorhanden
                if (isset($_SESSION['errors']['mac_address'])) {
                    ErrorHandler::displayErrors('mac_address');
                } else if (isset($_SESSION['errors']['exists_mac_address'])) {
                    ErrorHandler::displayErrors('exists_mac_address');
                }
                ?>
            </div>
        </div>
        
        <div class="formDiv">
            <div class="inner_container">
                <label for="username">Benutzername:</label> <!-- Label für Benutzername -->
            </div>
            <div class="inner_container">
                <?php
                // Eingabefeld für den Benutzernamen mit Sicherheitsmaßnahmen
                echo '<input type="text" class="form_input" id="username" name="username" value="' . htmlspecialchars($formData['username'] ?? '') . '" required>';
                // Fehlermeldungen für Benutzernamen anzeigen, falls vorhanden
                if (isset($_SESSION['errors']['exists_username'])) {
                    ErrorHandler::displayErrors('exists_username');
                }
                ?>
            </div>
        </div>
        
        <div class="formDiv">
            <div class="inner_container">
                <label for="password">Kennwort:</label> <!-- Label für Kennwort -->
            </div>
            <div class="inner_container" id="pwFeld">
                <?php
                // Eingabefeld für das Kennwort mit Sicherheitsmaßnahmen
                echo '<input type="password" class="form_input" id="password" name="password" value="' . htmlspecialchars($formData['password'] ?? '') . '" required placeholder="Mindestens 6 Zeichen">';
                // Fehlermeldungen für Kennwort anzeigen, falls vorhanden
                if (isset($_SESSION['errors']['password'])) {
                    ErrorHandler::displayErrors('password');
                }
                ?>             
                <div style="display: flex; flex-direction: row; margin-top: 1ch;">                    
                    <input type="checkbox" id="pwVisible" name="pwVisible" onchange="togglePW()">            
                    <label style="user-select: none; font-size: 1.6ch; line-height: 20px;" for="pwVisible">Kennwort anzeigen</label>
                    <!-- Checkbox zum Anzeigen des Kennworts -->
                </div>
            </div>
        </div>
        
        <div class="formDiv" id="descriptionDiv">
            <div class="inner_container">
                <label for="description">Beschreibung:</label> <!-- Label für Beschreibung -->
            </div>
            <div class="inner_container">
                <?php
                // Textbereich für die Beschreibung mit Sicherheitsmaßnahmen
                echo '<textarea class="form_input" id="description" name="description" rows="4" cols="19" placeholder="Optional">' . htmlspecialchars($formData['description'] ?? '') . '</textarea>';
                ?>
            </div>
        </div>

        <!-- CSRF-Token für zusätzliche Sicherheit -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

        <div class="btnDiv">
            <button type="submit" class="buttonForm">Speichern</button>    
            <!-- Button zum Absenden des Formulars -->
        </div>    
    </form>
    <form method="post">
        <button type="submit">Zurück</button>
        <!-- Button, um zur vorherigen Seite zurückzukehren -->
    </form>    
    <script src="../assets/js/add_device.js"></script> <!-- JavaScript-Datei für interaktive Funktionen einfügen -->
</div>
</body>
</html>
