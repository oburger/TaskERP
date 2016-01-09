<?php

if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"]) && permission($_COOKIE["nickname"], "upload")) {
    
    // Datei in tmp-Ordner hochladen
    print move_uploaded_file ($_FILES['datei']['tmp_name'], getcwd()."/tmp/".$_FILES['datei']['name']);
    
    // CSV-Datei auslesen und wiedergeben
    echo "<table>";
    $handle = fopen(getcwd()."/tmp/".$_FILES['datei']['name'], "r");
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $num = count($data);
            echo "<tr>";

            $eintrag = 0;

            $auftragskonto = 0;
            $buchungstag = 0;
            $valutadatum = 0;
            $buchungstext = 0;
            $verwendungszweck = 0;
            $beguenstigter_zahlungspflichtiger = 0;
            $kontonummer = 0;
            $blz = 0;
            $betrag = 0;
            $waehrung = 0;
            $info = 0;

            for($c=0; $c < $num; $c++){		
                    //echo "<td>".$data[$c]."</td>";
                
                    $data[$c] = iconv("", "UTF-8", $data[$c]);

                    switch($c) {
                            case 0:
                                    $auftragskonto = $data[$c];
                                    echo "<td>".$data[$c]."</td>";
                                    break;
                            case 1:
                                    $buchungsdatum = preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $data[$c]);
                                    echo "<td>".preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $data[$c])."</td>";
                                    break;
                            case 2:
                                    $valutdatum = preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $data[$c]);
                                    echo "<td>".preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $data[$c])."</td>";
                                    break;
                            case 3:
                                    $buchungstext = $data[$c];
                                    echo "<td>".$data[$c]."</td>";
                                    break;
                            case 4:
                                    $verwendungszweck = $data[$c];
                                    echo "<td>".$data[$c]."</td>";
                                    break;
                            case 5:
                                    $beguenstigter_zahlungspflichtiger = $data[$c];
                                    echo "<td>".$data[$c]."</td>";
                                    break;
                            case 6:
                                    $kontonummer = $data[$c];
                                    echo "<td>".$data[$c]."</td>";
                                    break;
                            case 7:
                                    $blz = $data[$c];
                                    echo "<td>".$data[$c]."</td>";
                                    break;
                            case 8:
                                    $betrag = str_replace(",",".",$data[$c]);
                                    echo "<td>".str_replace(",",".",$data[$c])."</td>";
                                    break;
                            case 9:
                                    $waehrung = $data[$c];
                                    echo "<td>".$data[$c]."</td>";
                                    break;
                            case 10:
                                    $info = $data[$c];
                                    echo "<td>".$data[$c]."</td>";
                                    break;
                    }	

            }
            echo "</tr>";

            // Daten in MySQL-Datenbank laden
            $sql = "INSERT INTO account_activity "
                    . "(Auftragskonto, Buchungstag, Valutadatum, Buchungstext, "
                    . "Verwendungszweck, Beguenstigter_Zahlungspflichtiger, "
                    . "Kontonummer, BLZ, Betrag, Waehrung, Info) "
                    . "VALUES ('".$auftragskonto."','".$buchungsdatum."',"
                    . "'".$valutdatum."','".$buchungstext."','".$verwendungszweck."',"
                    . "'".$beguenstigter_zahlungspflichtiger."','".$kontonummer."',"
                    . "'".$blz."','".$betrag."','".$waehrung."','".$info."')";
            $conn->query($sql);
    }
    echo "</table>";
    
} else {
	die("Fehler bei der Authentifizierung.");
}

?>