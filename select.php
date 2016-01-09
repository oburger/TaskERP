<?php

// Wird verwendet um nur die verknüpften, zusammengehörigen Dateien anzuzeigen

// Authentifizierung
if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"]) && permission($_COOKIE["nickname"], "documents") && permission($_COOKIE["nickname"], "account_activity")) {
    
//    $conn = new mysqli("localhost","root","","taskerp");
//    if($conn->connect_error) {
//            die("Connection failed");
//    }
    
    if($_GET["page"] == "select_doc") {
        // Abfrage Umsatzdaten
        $sql = "SELECT * FROM documents WHERE ID = '".$_GET["ID"]."';";	
        $result = $conn->query($sql);

?>
        <table class="table">
		<tr>
			<th>Aktionen</th>
			<th>ID</th>
			<th>Dateiname</th>
			<th>Kontakt</th>
			<th>Beschreibung</th>
			<th>Art</th>
			<th>Erinnerung</th>
			<th>Erledigt</th>
			<th>Datum</th>
			<th>Eingetragen</th>
			<th>Betrag</th>
		</tr>
<?php
        echo "<tr>";
        $row = $result->fetch_assoc();
			echo "<td><a href=\"update.php?ID=".$row["ID"]."\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></a></td>";
			echo "<td>".$row["ID"]."</td>";
			echo "<td><a href=\"uploads/".substr($row["Dateiname"],0,8)."/".$row["Dateiname"]."\">".$row["Dateiname"]."</a></td>";
			echo "<td>".$row["Kontakt"]."</td>";
			echo "<td>".$row["Beschreibung"]."</td>";
			echo "<td>".$row["Art"]."</td>";
			echo "<td>".$row["Erinnerung"]."</td>";
			echo "<td>".$row["Erledigt"]."</td>";
			echo "<td>".$row["Datum"]."</td>";
			echo "<td>".$row["Eingetragen"]."</td>";
			echo "<td>".$row["Betrag"]."</td>";
        echo "</tr>";

?>
        </table>
	
<?php
	
    $conn->close();
    }
    
    if($_GET["page"] == "select_acc_act") {
        // Abfrage Umsatzdaten
        $sql = "SELECT * FROM account_activity WHERE ID = '".$_GET["ID"]."';";	
        $result = $conn->query($sql);

?>
        <table class="table">
		<tr>
			<th>Aktionen</th>
			<th>ID</th>
                        <th>Buchungstag</th>
			<th>Buchungstext</th>
			<th>Verwendungszweck</th>
			<th>Begünstigter/Zahlungspflichtiger</th>
			<th>Kontonummer</th>
			<th>Bankleitzahl</th>
			<th>Betrag</th>
			<th>Währung</th>
			<th>Info</th>
			<th>Notiz</th>
		</tr>
<?php
        echo "<tr>";
        $row = $result->fetch_assoc();
			echo "<td><a href=\"update.php?ID=".$row["ID"]."\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></a></td>";
			echo "<td>".$row["ID"]."</td>";
                        echo "<td>".$row["Buchungstag"]."</td>";
			echo "<td>".$row["Buchungstext"]."</td>";
			echo "<td>".$row["Verwendungszweck"]."</td>";
			echo "<td>".$row["Beguenstigter_Zahlungspflichtiger"]."</td>";
			echo "<td>".$row["Kontonummer"]."</td>";
			echo "<td>".$row["BLZ"]."</td>";
			echo "<td>".$row["Betrag"]."</td>";
			echo "<td>".$row["Waehrung"]."</td>";
			echo "<td>".$row["Info"]."</td>";
			echo "<td>".$row["Notiz"]."</td>";
        echo "</tr>";

?>
        </table>
	
<?php
	
    $conn->close();
    }
} else {
    die("Fehler bei der Authentifizierung.");
}

?>