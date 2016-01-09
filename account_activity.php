<?php

if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"]) && permission($_COOKIE["nickname"], "account_activity")) {
    
//    // Verbindung zu Datenbank aufbauen
//    $conn = new mysqli("localhost","root","","taskerp");
//    if($conn->connect_error) {
//            die("Connection failed");
//    }    
    
    // Spezialroutine:
    // Prüfen des GET-Parameters, um festzustellen ob Dokument verknüpft werden soll
    if(isset($_GET["acc_act_id"]) && isset($_GET["doc_id"])) {
        $sql = "INSERT INTO rel_doc_acc_act (doc_id, acc_act_id) VALUES (".$_GET["doc_id"].", ".$_GET["acc_act_id"].");";
        $result = $conn->query($sql);
        
        echo "<p>Erfolgreich verknüpft.</p>";
    }

    // Abfrage Umsatzdaten
    $sql = "SELECT * FROM account_activity;";	
    $result = $conn->query($sql);

    // Abfrage der Relation zu Dokumenten
    $sql_rel = "SELECT * FROM rel_doc_acc_act;";
    $result_rel = $conn->query($sql_rel);

    ?>

    <table data-toggle="table" data-sort-name="valutadatum" data-sort-order="desc" data-height="900" data-show-columns="true" data-search="true">
		<thead>
                    <tr>  
                        <th data-checkbox="true"></th>
                        <th>Aktionen</th>
                        <?php
                            if(isset($_GET["doc_id"])) {
                                echo "<th data-field=\"id\" data-sortable=\"true\" data-visible=\"true\">ID</th>";
                            } else {
                                echo "<th data-field=\"id\" data-sortable=\"true\" data-visible=\"false\">ID</th>";
                            }
                        ?>
                        <th data-field="valutadatum" data-sortable="true" data-visible="true">Valutadatum</th>
                        <th data-field="buchungstag" data-sortable="true" data-visible="false">Buchungstag</th>
                        <th data-field="buchungstext" data-sortable="true">Buchungstext</th>
                        <th data-field="verwendungszweck" data-sortable="true">Verwendungszweck</th>
                        <th data-field="beguenstigter" data-sortable="true">Beg&uuml;nstigter/Zahlungspflichtiger</th>
                        <th data-field="kontonummer" data-sortable="true" data-visible="false">Kontonummer</th>
                        <th data-field="bankleitzahl" data-sortable="true" data-visible="false">Bankleitzahl</th>
                        <th data-field="betrag" data-sortable="true">Betrag</th>
                        <th data-field="kummuliert" data-sortable="true" data-visible="false">Kummuliert</th>
                        <th data-field="waehrung" data-sortable="true" data-visible="false">W&auml;hrung</th>
                        <th data-field="info" data-sortable="true" data-visible="false">Info</th>
                        <th data-field="notiz" data-sortable="true" data-visible="false">Notiz</th>
                        <th data-field="zugdokument" data-sortable="true" data-visible="false">Zug. Dokument</th>
                    </tr>
                </thead>
                <tbody>        
            
    <?php
    
    // Kontostand am 01.01.2015
    $kontostand_beginn = 40522.95;
    $kontostand = $kontostand_beginn;
    
    if($result->num_rows > 0 ) {
            while($row = $result->fetch_assoc()) {
                    $kontostand += $row["Betrag"];
                    
                    
                    echo "<tr class=\"".checkAccountActivity($row["Buchungstext"])."\">";
                    echo "<td></td><td><a href=\"index.php?page=link_doc&ID=".$row["ID"]."\"><span class=\"glyphicon glyphicon-link\" aria-hidden=\"true\"></span></a></td>";

                    if($_GET["page"] != "link_acc_act") {
                        echo "<td>".$row["ID"]."</td>";
                    } else {
                        echo "<td><a href=\"index.php?page=link_acc_act&doc_id=".$_GET["doc_id"]."&acc_act_id=".$row["ID"]."\">".$row["ID"]."</a></td>";
                    }

                    echo "<td>".$row["Valutadatum"]."</td>";
                    echo "<td>".$row["Buchungstag"]."</td>";
                    echo "<td>".$row["Buchungstext"]."</td>";
                    echo "<td>".$row["Verwendungszweck"]."</td>";
                    echo "<td>".$row["Beguenstigter_Zahlungspflichtiger"]."</td>";
                    echo "<td>".$row["Kontonummer"]."</td>";
                    echo "<td>".$row["BLZ"]."</td>";
                    echo "<td>".$row["Betrag"]."</td>";
                    echo "<td>".$kontostand."</td>";
                    echo "<td>".$row["Waehrung"]."</td>";
                    echo "<td>".$row["Info"]."</td>";
                    echo "<td>".$row["Notiz"]."</td>";

                    // Zugehörige Dokumentendatensätze auslesen
                    if($result_rel->num_rows > 0 ) {
                        echo "<td>";
                        while($row_rel = $result_rel->fetch_assoc()) {
                            if($row["ID"] == $row_rel["acc_act_id"]) {
                                echo "<a href=\"index.php?page=select_doc&ID=".$row_rel["doc_id"]."\">".$row_rel["doc_id"]."</a> ";
                            }
                        }
                        echo "</td>";
                        // Pointer im Array zurücksetzten
                        $result_rel->data_seek(0);
                    }    

                    echo "</tr>";
            }
    }
    ?>
                </tbody>
    </table>

    <?php
    
    echo "<p>Kontostand zu Beginn: $kontostand_beginn. <br /> Aktueller Kontostand: $kontostand.</p>";

    $conn->close();
} else {
    die("Fehler bei der Authentifizierung.");
}

?>