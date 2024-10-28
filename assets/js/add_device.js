// Funktion, um das IP-Feld basierend auf dem DHCP-Checkbox-Status umzuschalten
function toggleIP() {
    // Ruft das DHCP-Checkbox-Element ab
    var dhcpCheckbox = document.getElementById('dhcp');
    // Ruft das IP-Feld-Element ab
    var ipField = document.getElementById('ipFeld');
    
    // Überprüft, ob die DHCP-Checkbox aktiviert ist.
    // Wenn aktiviert, wird das IP-Feld ausgeblendet, da DHCP keine manuelle IP-Eingabe benötigt.
    // Wenn nicht aktiviert, wird das IP-Feld sichtbar gemacht, da eine manuelle IP-Eingabe erforderlich ist.
    dhcpCheckbox.checked ? ipField.style.display = 'none' : ipField.style.display = 'flex';
}

// Funktion, um die Passwortsichtbarkeit basierend auf einer Checkbox umzuschalten
function togglePW() {
    // Ruft das Passwort-Eingabefeld ab
    var passwordFeld = document.getElementById('password');
    // Ruft das Checkbox-Element ab, das die Passwortsichtbarkeit steuert
    var pwVisibleCheckbox = document.getElementById('pwVisible');
    
    // Überprüft, ob die Checkbox für Passwortsichtbarkeit aktiviert ist.
    // Wenn aktiviert, wird das Passwort als Klartext angezeigt ('text').
    // Wenn nicht aktiviert, bleibt das Passwortfeld im versteckten Modus ('password').
    passwordFeld.type = pwVisibleCheckbox.checked ? 'text' : 'password';
}
