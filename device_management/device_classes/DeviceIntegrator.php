<?php

class DeviceIntegrator {
    // Private Eigenschaften, die die Geräteinformationen speichern
    private string $csvPath = 'device_data/data.csv'; // Pfad zur CSV-Datei, in der die Gerätedaten gespeichert werden
    private string $hostname; // Hostname des Geräts
    private string $domain; // Domain des Geräts
    private string $ipAddress; // IP-Adresse des Geräts
    private string $dhcp; // DHCP-Status des Geräts (Ja/Nein)
    private string $macAddress; // MAC-Adresse des Geräts
    private string $username; // Benutzername für das Gerät
    private string $password; // Passwort für das Gerät
    private ?string $description = null; // Beschreibung ist optional, daher nullable
    private bool $duplicate; // Flag zur Überprüfung, ob ein Duplikat vorhanden ist
    
    /**
     * Konstruktor der Klasse DeviceIntegrator.
     * Übernimmt die Validierung der Eingabedaten und weist die Werte den Eigenschaften zu.
     * @param array $data - Die Eingabedaten als assoziatives Array
     */
    public function __construct(array $data) {
        // Validierung und Zuweisung der Eingabedaten
        $this->hostname = DeviceValidator::validateHostname($data['hostname']);    
        $this->domain = DeviceValidator::validateDomain($data['domain']);    
        $this->dhcp = isset($data['dhcp']) ? 'Yes' : 'No'; // Setzt den DHCP-Status        
        $this->ipAddress = DeviceValidator::validateIpAddress($data['ip_address'], $this->dhcp);
        $this->macAddress = DeviceValidator::validateMacAddress($data['mac_address']);   
        $this->username = $data['username']; // Benutzername (nicht validiert hier)
        $this->password = DeviceValidator::validatePassword($data['password']);
        $this->description = DeviceValidator::validateDescription($data['description']); // Beschreibung validieren
        $this->duplicate = DeviceValidator::validateUniqueValues($data['hostname'], $data['domain'], $data['mac_address'], $data['username'], $this->csvPath); // Überprüfung auf Duplikate
    }

    // Methode zum Hashen des Passworts
    private function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT); // Hashen des Passworts für die sichere Speicherung
    }
    /**
     * Speichert die Gerätedaten in einer CSV-Datei.
     * Wenn die Datei nicht existiert, wird sie erstellt und eine Kopfzeile hinzugefügt.
     * @param string $filename - Der Pfad zur CSV-Datei
     */
    public function saveToCSV(string $filename) {
        // Array mit den Gerätedaten, die in die CSV-Datei geschrieben werden
        $data = [
            $this->hostname,
            $this->domain,
            $this->ipAddress,
            $this->dhcp,
            $this->macAddress,
            $this->username,
            $this->hashPassword($this->password), // Passwort wird gehasht, bevor es gespeichert wird
            $this->description
        ];
    
        // Prüft, ob die Datei existiert; wenn nicht, wird sie erstellt und eine Kopfzeile hinzugefügt
        if (!file_exists($filename)) {
            $file = fopen($filename, 'w'); // Datei im Schreibmodus öffnen
            if ($file === false) {
                ErrorHandler::get_error('2'); // Datei konnte nicht erstellt werden
            }
    
            // Fügt eine Kopfzeile zur CSV-Datei hinzu, um die Spalten zu beschreiben
            fputcsv($file, ['Hostname', 'Domain', 'IP Address', 'DHCP', 'MAC Address', 'Username', 'Password', 'Description']);
            fclose($file); // Datei schließen
        }
    
        // Öffnet die Datei im Append-Modus ('a'), um die Gerätedaten anzuhängen
        $file = fopen($filename, 'a'); // Datei im Anhängemodus öffnen
        if ($file === false) {
            ErrorHandler::get_error('1'); // Datei konnte nicht geöffnet werden
        }
    
        // Fügt die Gerätedaten als neue Zeile in der CSV-Datei hinzu
        fputcsv($file, $data);
        fclose($file); // Datei schließen

        //TODO Überprüfung der Datei ob alles hinzugefügt wurde




    }
    /**
     * Gibt die Gerätedaten als assoziatives Array zurück.
     * @return array - Ein Array mit den Gerätedaten, das z.B. für die Anzeige oder Weiterverarbeitung verwendet werden kann
     */
    public function getData() {
        return [
            'Hostname' => $this->hostname,
            'Domain' => $this->domain,
            'IP Address' => $this->ipAddress,
            'DHCP' => $this->dhcp,
            'MAC Address' => $this->macAddress,
            'Username' => $this->username,
            'Password' => $this->password, // Achtung: Das Passwort wird hier im Klartext zurückgegeben. In der Regel sollte dies vermieden werden.
            'Description' => $this->description,
        ];
    }
}
