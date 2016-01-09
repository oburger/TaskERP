<?php

// Authentifizierung
if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"]) && permission($_COOKIE["nickname"], "documents")) {
	/*print $verbindung = mysql_connect("localhost","root","") or die("Keine Verbindung");
	print mysql_select_db("TaskERP") or die("Keine Datenbank");
	$sql = "SELECT * FROM documents;";
	$result = mysql_query($sql);
	
	print $result->num_rows;*/
	
	
//        // Datenbankverbindung    
//	$conn = new mysqli("localhost","root","","taskerp");
//	if($conn->connect_error) {
//		die("Connection failed");
//	}	
        
        // Spezialroutine:
        // Prüfen des GET-Parameters, um festzustellen ob Dokument verknüpft werden soll
        if(isset($_GET["doc_id"])) {
            $sql = "INSERT INTO rel_doc_acc_act (doc_id, acc_act_id) VALUES (".$_GET["doc_id"].", ".$_GET["ID"].");";
            $result = $conn->query($sql);

            echo "<p>Erfolgreich verknüpft.</p>";
        }
                
        // Abfrage Dokumentendaten
	$sql = "SELECT * FROM documents ORDER BY ID DESC;";
        //$sql = "SELECT * FROM documents WHERE Kontakt = 14 AND Art = 'Rechnung' ORDER BY ID DESC;";
	$result = $conn->query($sql);
        
        // Abfrage der Relation zu Umsatz
        $sql_rel = "SELECT * FROM rel_doc_acc_act;";
        $result_rel = $conn->query($sql_rel);
        
	?>
	
        <!-- Tabellenkopf -->
        
        <table data-toggle="table" data-sort-name="datum" data-sort-order="desc" data-height="900" data-show-columns="true" data-search="true">
        <!--data-detail-view="true" data-detail-formatter="detailFormatter"--> 
		<thead>
                    <tr>  
                        <th data-field="checkbox" data-checkbox="true"></th>
                        <th data-field="action" >Aktionen</th>
                        <th data-field="id" data-sortable="true" data-visible="false">ID</th>
                        <th data-field="dateiname" data-sortable="true" data-visible="false">Dateiname</th>
                        <th data-field="kontakt" data-sortable="true">Kontakt</th>
                        <th data-field="beschreibung" data-sortable="true">Beschreibung</th>
                        <th data-field="art" data-sortable="true">Art</th>
                        <th data-field="erinnerung" data-sortable="true" data-visible="false">Erinnerung</th>
                        <th data-field="erledigt" data-sortable="true" data-visible="false">Erledigt</th>
                        <th data-field="datum" data-sortable="true">Datum</th>
                        <th data-field="eingetragen" data-sortable="true" data-visible="false">Eingetragen</th>
                        <th data-field="betrag" data-sortable="true" data-visible="false">Betrag</th>
                        <th data-field="zugumsatz" data-sortable="true" data-visible="false">Zug. Umsatz</th>
                    </tr>
                </thead>
                <tbody>
	<?php
        
        // Durchlauf des Arrays mit Datenbankresultaten
	if($result->num_rows > 0 ) {
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			//echo "<td></td><td><a href=\"uploads/".substr($row["Dateiname"],0,8)."/".$row["Dateiname"]."\"><span class=\"glyphicon glyphicon-open-file\" aria-hidden=\"true\"></a></span><a href=\"update.php?ID=".$row["ID"]."\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></a><a href=\"index.php?page=link_acc_act&ID=".$row["ID"]."\"><span class=\"glyphicon glyphicon-link\" aria-hidden=\"true\"></span></a><a href=\"index.php?page=link&doc_id=".$row["ID"]."\"><span class=\"glyphicon glyphicon-link\" aria-hidden=\"true\"></span></a></td>";
			echo "<td></td><td><a href=\"uploads/".substr($row["Dateiname"],0,8)."/".$row["Dateiname"]."\"><span class=\"glyphicon glyphicon-open-file\" aria-hidden=\"true\"></a></span><a href=\"index.php?page=link&doc_id=".$row["ID"]."\"><span class=\"glyphicon glyphicon-link\" aria-hidden=\"true\"></span></a></td>";
                        
                        if($_GET["page"] != "link_doc") {
                            echo "<td>".$row["ID"]."</td>";
                        } else {
                            echo "<td><a href=\"index.php?page=link_doc&ID=".$_GET["ID"]."&doc_id=".$row["ID"]."\">".$row["ID"]."</a></td>";
                        }
                        
			echo "<td><a href=\"uploads/".substr($row["Dateiname"],0,8)."/".$row["Dateiname"]."\">".$row["Dateiname"]."</a></td>";
			echo "<td>";                        
                        // Prüfen ob Kontakt numerisch -> nur wichtig während Umstellung auf Kontaktfunktion, danach entfernen
                        if(is_numeric($row["Kontakt"])) {
                            $sql_num = "SELECT * FROM contacts_firm WHERE id = ".$row["Kontakt"].";";
                            $result_num = $conn->query($sql_num);
                            $row_num = $result_num->fetch_assoc();
                            echo $row_num["firm"];
                        } else {
                            echo $row["Kontakt"];
                        }                         
                        "</td>";
                        
			echo "<td>".$row["Beschreibung"]."</td>";
			echo "<td>".$row["Art"]."</td>";
			echo "<td>".$row["Erinnerung"]."</td>";
			echo "<td>".$row["Erledigt"]."</td>";
			echo "<td>".$row["Datum"]."</td>";
			echo "<td>".$row["Eingetragen"]."</td>";
			echo "<td>".$row["Betrag"]."</td>";
                        
                        // Zugehörige Umsatzdatensätze auslesen
                        if($result_rel->num_rows > 0 ) {
                            echo "<td>";
                            while($row_rel = $result_rel->fetch_assoc()) {
                                if($row["ID"] == $row_rel["doc_id"]) {
                                    echo "<a href=\"index.php?page=select_acc_act&ID=".$row_rel["acc_act_id"]."\">".$row_rel["acc_act_id"]."</a> ";
                                }
                            }
                            echo "</td>";
                            // Pointer im Array zurücksetzten
                            $result_rel->data_seek(0);
                        }     
                        echo "</tr>";
		}
                
                //$result_rel->free();
                //$result->free();
	} else {
            echo "Keine Daten verfügbar.";
        }

	?>
                </tbody>
	</table>
        
<!--        <script>
            function detailFormatter(index, row) {
                var html = [];
                $.each(row, function (key, value) {
                    if(id = 441) {
                        html.push('<p><b>yessss:</b> ' + value + '</p>');
                    } else {
                        html.push('<p><b>' + key + ':</b> ' + value + '</p>');
                    }
                    
                });
                return html.join('');
            }
        </script>-->
        
	<?php
	
	$conn->close();
	
} else {
	die("Fehler bei der Authentifizierung.");
}

?>