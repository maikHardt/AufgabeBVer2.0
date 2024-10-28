<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Seiteneinstellungen -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/index.css">
    <title>Aufgabe B</title>
</head>
<body>
    <!-- Seitenkopf mit zwei Navigationsbuttons -->
    <div id="header">
        <div id="inner_header">
            <!-- Button zur Geräteübersicht -->
            <div id="div_device_overview">
                <form method="get" action="device_management/device_overview.php">
                    <button type="submit">Zur Übersicht</button>
                </form>
            </div>
            <!-- Button zum Hinzufügen eines Netzwerks -->
            <div id="div_device_add">
                <form method="get" action="device_management/add_device.php">
                    <button type="submit">IT-Netzwerk hinzufügen</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Titel der Seite -->
    <h1 align="center">"Fertiges Kunden-Netzwerk"</h1>
</body>
</html>
