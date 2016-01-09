<?php

if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"]) && permission($_COOKIE["nickname"], "upload")) {
    
    date_default_timezone_set("Europe/Berlin");
    $timestamp = time();
    $ordnername = date("Ymd", $timestamp); 
    //echo $ordnername."<br/>";

    // Ordner erstellen
    if(is_dir(getcwd()."/uploads/".$ordnername) == false) {
            $ordner_erstellen = mkdir(getcwd()."/uploads/".$ordnername, 0777);		
            if($ordner_erstellen != true) {
                    echo "Ordner konnte nicht erstellt werden.<br />";
            }
    }

    // Datei uploaden
    $dateiname = date("YmdHis", $timestamp).".pdf";
    $upload = move_uploaded_file($_FILES['datei']['tmp_name'], getcwd()."/uploads/".$ordnername."/".$dateiname);
    if($upload != true) {
            echo "Dateiupload nicht erfolgreich.<br />";
    }

    // Datenbankeintrag
    $kontakt = htmlspecialchars($_POST["kontakt"]);
    $betreff = htmlspecialchars($_POST["betreff"]);
    $art = htmlspecialchars($_POST["art"]);
    $erinnerung = htmlspecialchars(preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $_POST["erinnerung"]));
    $erledigt = htmlspecialchars($_POST["erledigt"]);
    $datum = htmlspecialchars(preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $_POST["datum"]));
    $eingetragen = date("y-m-d", $timestamp);
    $betrag = htmlspecialchars($_POST["betrag"]);
//    $verbindung = mysql_connect("localhost","root","") or die("Keine Verbindung");
//    mysql_select_db("taskerp") or die("Keine Datenbank");
    $eintrag = "INSERT INTO documents (Dateiname, Kontakt, Beschreibung, Art, Erinnerung, Erledigt, Datum, Eingetragen, Betrag) VALUES ('$dateiname','$kontakt','$betreff','$art','$erinnerung','$erledigt','$datum','$eingetragen','$betrag')";
    
    if($conn->query($eintrag)) {
        echo "Datenbankeintrag erfolgreich.</br >";
    } else {
         die("Datenbankeintrag fehlgeschlagen.");
    }
    
    echo "<a href=\"index.php?page=upload\">Zurück</a>";
    
    //$verbindung->close();
    
} else {
	die("Fehler bei der Authentifizierung.");
}

?>